<?php
class HomeController extends BaseController {
	public function index()
	{
		$tweets = Es::getIndex('tweets');

		// Build the query
		$query = new Elastica\Query();
		$query->setLimit(100);

		if (Input::has('q') && Input::get('q')) {
			$elasticaQueryString = new Elastica\Query\QueryString;
			$elasticaQueryString->setDefaultOperator('AND');
			$elasticaQueryString->setQuery(Input::get('q', ''));
			$query->setQuery($elasticaQueryString);
		}

		if (Input::has('user')) {
			$userFilter = new Elastica\Filter\Term(['user' => Input::get('user')]);
			$query->addFilter($userFilter);
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

	public function getSearch()
	{

	}
}
