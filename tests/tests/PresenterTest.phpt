<?php declare(strict_types = 1);

namespace Tests;

use AlesWita\VisualPaginator\VisualPaginator;
use App\TestPresenter;
use Nette\Application\IPresenterFactory;
use Nette\Application\Request;
use Nette\Application\Responses\TextResponse;
use Nette\DI\Container;
use Nette\Http\IRequest;
use Nette\InvalidArgumentException;
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

		/** @var IPresenterFactory $presenterFactory */
		$presenterFactory = $this->container->getByType(IPresenterFactory::class);

		$this->presenterFactory = $presenterFactory;
	}

	public function test01(): void
	{
		/** @var TestPresenter $presenter */
		$presenter = $this->presenterFactory->createPresenter('Test');

		$request = new Request('Test', IRequest::GET);

		/** @var TextResponse $response */
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
		Assert::same('/?paginator-page=1&do=paginator-paginate', $actual['@attributes']['href']);

		$actual = (array) $tag[1];
		Assert::same('2', $actual[0]);
		Assert::same('/?paginator-page=2&do=paginator-paginate', $actual['@attributes']['href']);

		$actual = (array) $tag[2];
		Assert::same('3', $actual[0]);
		Assert::same('/?paginator-page=3&do=paginator-paginate', $actual['@attributes']['href']);

		$actual = (array) $tag[3];
		Assert::same('100', $actual[0]);
		Assert::same('/?paginator-page=100&do=paginator-paginate', $actual['@attributes']['href']);

		$actual = (array) $tag[4];
		Assert::same('»', $actual[0]);
		Assert::same('/?paginator-page=2&do=paginator-paginate', $actual['@attributes']['href']);
	}

	public function test02(): void
	{
		/** @var TestPresenter $presenter */
		$presenter = $this->presenterFactory->createPresenter('Test');

		/** @var VisualPaginator $paginator */
		$paginator = $presenter['paginator'];

		$paginator->setItemCount(1000);

		Assert::exception(static function () use ($paginator): void {
			$paginator->setItemsPerPage(10);
		}, InvalidArgumentException::class, 'AlesWita\VisualPaginator\VisualPaginator::setItemsPerPage(): can not set items per page. You can enabled it by set $canSetItemsPerPage to true.');

		$paginator->canSetItemsPerPage = true;

		Assert::exception(static function () use ($paginator): void {
			$paginator->setItemsPerPage(15);
		}, InvalidArgumentException::class, 'AlesWita\VisualPaginator\VisualPaginator::setItemsPerPage(): $itemsPerPageList has not key "15".');

		$paginator->setItemsPerPage(20);
		$paginator->loadState([]);

		Assert::same(20, $paginator->getItemsPerPage());
	}

}

(new PresenterTest($container))->run();
