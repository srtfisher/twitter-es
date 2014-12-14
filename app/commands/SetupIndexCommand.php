<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class SetupIndexCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'setup-index';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Setup elasticsearch indexes.';

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		$index = Es::getIndex('tweets');

		try {
			$index->delete();
		} catch (Exception $e) {
			$this->error('Could not delete tweets index: '.$e->getMessage());
		}

		// Create the index
		$index->create([
			'number_of_shards' => 1,
			'number_of_replicas' => 1,
		]);

		$this->info('Index created.');

		// Mapping for type
		$type = $index->getType('tweet');

		$mapping = new \Elastica\Type\Mapping();
		$mapping->setType($type);
		$mapping->setParam('index_analyzer', 'indexAnalyzer');
		$mapping->setParam('search_analyzer', 'searchAnalyzer');

		// Set mapping
		$mapping->setProperties(array(
			'id'       => array('type' => 'integer', 'include_in_all' => FALSE),
			'message'  => array('type' => 'string', 'include_in_all' => TRUE),
			'timestamp'  => ['type' => 'date', 'format' => 'yyyy-MM-dd HH:mm:ss'],
		));
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
