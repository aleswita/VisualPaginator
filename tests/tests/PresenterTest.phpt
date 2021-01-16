<?php declare(strict_types = 1);

namespace Tests;

use Nette\Application\IPresenterFactory;
use Nette\Application\Request;
use Nette\DI\Container;
use Nette\Http\IRequest;
use Tester\Assert;
use Tester\DomQuery;
use Tester\TestCase;

$container = require __DIR__ . '/../bootstrap.php';

final class PresenterTest extends TestCase
{

	private Container $container;

	private IPresenterFactory $presenterFactory;

	public function __construct(Container $container)
	{
		$this->container = $container;

		/** @var \Nette\Application\IPresenterFactory $presenterFactory */
		$presenterFactory = $this->container->getByType(IPresenterFactory::class);

		$this->presenterFactory = $presenterFactory;
	}

	public function test01(): void
	{
		/** @var \App\TestPresenter $presenter */
		$presenter = $this->presenterFactory->createPresenter('Test');

		$request = new Request('Test', IRequest::GET);

		/** @var \Nette\Application\Responses\TextResponse $response */
		$response = $presenter->run($request);

		$dom = DomQuery::fromHtml(
			$response->getSource()
				->renderToString()
		);

		$tag = $dom->find('span');
		Assert::count(2, $tag);

		$actual = (array) $tag[0];
		Assert::same('«', $actual[0]);

		$actual = (array) $tag[1];
		Assert::same('…', $actual[0]);

		$tag = $dom->find('a');
		Assert::count(5, $tag);

		$actual = (array) $tag[0];
		Assert::same('1', $actual[0]);
		Assert::same('/?paginator-page=1&paginator-itemsPerPage=10&do=paginator-paginate', $actual['@attributes']['href']);

		$actual = (array) $tag[1];
		Assert::same('2', $actual[0]);
		Assert::same('/?paginator-page=2&paginator-itemsPerPage=10&do=paginator-paginate', $actual['@attributes']['href']);

		$actual = (array) $tag[2];
		Assert::same('3', $actual[0]);
		Assert::same('/?paginator-page=3&paginator-itemsPerPage=10&do=paginator-paginate', $actual['@attributes']['href']);

		$actual = (array) $tag[3];
		Assert::same('100', $actual[0]);
		Assert::same('/?paginator-page=100&paginator-itemsPerPage=10&do=paginator-paginate', $actual['@attributes']['href']);

		$actual = (array) $tag[4];
		Assert::same('»', $actual[0]);
		Assert::same('/?paginator-page=2&paginator-itemsPerPage=10&do=paginator-paginate', $actual['@attributes']['href']);
	}

}

(new PresenterTest($container))->run();
