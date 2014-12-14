<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Elastica\Document as ElasticaDocument;
use Elastica\Bulk;
use Carbon\Carbon;

class PullInTweets extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'pull-in-tweets';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Pull in tweets.';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		// Retrieve the Tweets
		$search = Twitter::getSearch([
			'q' => 'NJIT',
			'count' => 100
		]);

		// Index the Tweets
		$documents = [];
		foreach ($search->statuses as $tweet) {
			$documents[] = $this->getDocumentForTweet($tweet);
		}

		$es = Es::getIndex('tweets');
		$type = $es->getType('tweet');
		$type->addDocuments($documents);
		$this->info('Done.');
	}

	protected function getDocumentForTweet($tweet) {
		return new ElasticaDocument($tweet->id, [
			'id' => $tweet->id,
			'message' => $tweet->text,
			'user' => $tweet->user->screen_name,
			'timestamp' => Carbon::parse($tweet->created_at)->toDateTimeString()
		]);
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(

		);
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array(

		);
	}

}
