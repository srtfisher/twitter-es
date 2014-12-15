<?php
class HomeController extends BaseController {
	public function index()
	{
		$tweets = Es::getIndex('tweets');

		// Build the query
		$query = new Elastica\Query();
		$query->setLimit(100);

		$query_string = Input::get('q', '');

		if (Input::has('user') && Input::get('user')) {
			$query_string .= ' user:'.Input::get('user');
		}

		if ($query_string) {
			$elasticaQueryString = new Elastica\Query\QueryString;
			$elasticaQueryString->setDefaultOperator('AND');
			$elasticaQueryString->setQuery($query_string);
			$query->setQuery($elasticaQueryString);
		}

		$query->setSort([
			['timestamp' => ['order' => 'desc']],
			//'_score'
		]);

		try {
			$results = $tweets->getType('tweet')->search($query);
		} catch (Exception $e) {
			Log::error($e);
		}

		return View::make('search')
			->with('results', $results);
	}
}
