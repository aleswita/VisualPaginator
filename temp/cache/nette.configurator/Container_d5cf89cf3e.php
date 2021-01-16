<?php
// source: C:\xampp_74\htdocs\VisualPaginator\tests/App/config/config.neon
// source: array

/** @noinspection PhpParamsInspection,PhpMethodMayBeStaticInspection */

declare(strict_types=1);

class Container_d5cf89cf3e extends Nette\DI\Container
{
	protected $tags = ['nette.inject' => ['application.1' => true, 'application.2' => true, 'test.presenter' => true]];
	protected $types = ['container' => 'Nette\DI\Container'];

	protected $aliases = [
		'application' => 'application.application',
		'httpRequest' => 'http.request',
		'httpResponse' => 'http.response',
		'nette.httpRequestFactory' => 'http.requestFactory',
		'nette.latteFactory' => 'latte.latteFactory',
		'nette.presenterFactory' => 'application.presenterFactory',
		'nette.templateFactory' => 'latte.templateFactory',
		'session' => 'session.session',
	];

	protected $wiring = [
		'Nette\DI\Container' => [['container']],
		'Nette\Application\Application' => [['application.application']],
		'Nette\Application\IPresenterFactory' => [['application.presenterFactory']],
		'Nette\Application\LinkGenerator' => [['application.linkGenerator']],
		'Nette\Http\RequestFactory' => [['http.requestFactory']],
		'Nette\Http\IRequest' => [['http.request']],
		'Nette\Http\Request' => [['http.request']],
		'Nette\Http\IResponse' => [['http.response']],
		'Nette\Http\Response' => [['http.response']],
		'Nette\Bridges\ApplicationLatte\LatteFactory' => [['latte.latteFactory']],
		'Nette\Application\UI\TemplateFactory' => [['latte.templateFactory']],
		'Nette\Http\Session' => [['session.session']],
		'Tracy\ILogger' => [['tracy.logger']],
		'Tracy\BlueScreen' => [['tracy.blueScreen']],
		'Tracy\Bar' => [['tracy.bar']],
		'AlesWita\Components\VisualPaginatorFactory' => [['01']],
		'Nette\Routing\Router' => [['02']],
		'Nette\Application\UI\Presenter' => [2 => ['test.presenter']],
		'Nette\Application\UI\Control' => [2 => ['test.presenter']],
		'Nette\Application\UI\Component' => [2 => ['test.presenter']],
		'Nette\ComponentModel\Container' => [2 => ['test.presenter']],
		'Nette\ComponentModel\Component' => [2 => ['test.presenter']],
		'Nette\Application\IPresenter' => [2 => ['test.presenter', 'application.1', 'application.2']],
		'Nette\Application\UI\Renderable' => [2 => ['test.presenter']],
		'ArrayAccess' => [2 => ['test.presenter']],
		'Nette\Application\UI\StatePersistent' => [2 => ['test.presenter']],
		'Nette\Application\UI\SignalReceiver' => [2 => ['test.presenter']],
		'Nette\ComponentModel\IContainer' => [2 => ['test.presenter']],
		'Nette\ComponentModel\IComponent' => [2 => ['test.presenter']],
		'App\TestPresenter' => [2 => ['test.presenter']],
		'NetteModule\ErrorPresenter' => [2 => ['application.1']],
		'NetteModule\MicroPresenter' => [2 => ['application.2']],
	];


	public function __construct(array $params = [])
	{
		parent::__construct($params);
		$this->parameters += [
			'appDir' => 'C:\xampp_74\htdocs\VisualPaginator\tests',
			'wwwDir' => 'C:\xampp_74\htdocs\VisualPaginator\tests\Tests',
			'vendorDir' => 'C:\xampp_74\htdocs\VisualPaginator\vendor',
			'debugMode' => false,
			'productionMode' => true,
			'consoleMode' => true,
			'tempDir' => 'C:\xampp_74\htdocs\VisualPaginator\tests/../temp',
		];
	}


	public function createService01(): AlesWita\Components\VisualPaginatorFactory
	{
		return new AlesWita\Components\VisualPaginatorFactory($this->getService('session.session'), null);
	}


	public function createService02(): Nette\Routing\Router
	{
		return App\RouterFactory::createRouter();
	}


	public function createServiceApplication__1(): NetteModule\ErrorPresenter
	{
		return new NetteModule\ErrorPresenter($this->getService('tracy.logger'));
	}


	public function createServiceApplication__2(): NetteModule\MicroPresenter
	{
		return new NetteModule\MicroPresenter($this, $this->getService('http.request'), $this->getService('02'));
	}


	public function createServiceApplication__application(): Nette\Application\Application
	{
		$service = new Nette\Application\Application(
			$this->getService('application.presenterFactory'),
			$this->getService('02'),
			$this->getService('http.request'),
			$this->getService('http.response')
		);
		$service->catchExceptions = true;
		$service->errorPresenter = 'Nette:Error';
		Nette\Bridges\ApplicationDI\ApplicationExtension::initializeBlueScreenPanel(
			$this->getService('tracy.blueScreen'),
			$service
		);
		return $service;
	}


	public function createServiceApplication__linkGenerator(): Nette\Application\LinkGenerator
	{
		return new Nette\Application\LinkGenerator(
			$this->getService('02'),
			$this->getService('http.request')->getUrl()->withoutUserInfo(),
			$this->getService('application.presenterFactory')
		);
	}


	public function createServiceApplication__presenterFactory(): Nette\Application\IPresenterFactory
	{
		$service = new Nette\Application\PresenterFactory(new Nette\Bridges\ApplicationDI\PresenterFactoryCallback($this, 1, null));
		$service->setMapping(['*' => 'App\*Presenter']);
		return $service;
	}


	public function createServiceContainer(): Container_d5cf89cf3e
	{
		return $this;
	}


	public function createServiceHttp__request(): Nette\Http\Request
	{
		return $this->getService('http.requestFactory')->fromGlobals();
	}


	public function createServiceHttp__requestFactory(): Nette\Http\RequestFactory
	{
		$service = new Nette\Http\RequestFactory;
		$service->setProxy([]);
		return $service;
	}


	public function createServiceHttp__response(): Nette\Http\Response
	{
		$service = new Nette\Http\Response;
		$service->cookieSecure = $this->getService('http.request')->isSecured();
		return $service;
	}


	public function createServiceLatte__latteFactory(): Nette\Bridges\ApplicationLatte\LatteFactory
	{
		return new class ($this) implements Nette\Bridges\ApplicationLatte\LatteFactory {
			private $container;


			public function __construct(Container_d5cf89cf3e $container)
			{
				$this->container = $container;
			}


			public function create(): Latte\Engine
			{
				$service = new Latte\Engine;
				$service->setTempDirectory('C:\xampp_74\htdocs\VisualPaginator\tests/../temp/cache/latte');
				$service->setAutoRefresh(false);
				$service->setContentType('html');
				Nette\Utils\Html::$xhtml = false;
				return $service;
			}
		};
	}


	public function createServiceLatte__templateFactory(): Nette\Application\UI\TemplateFactory
	{
		return new Nette\Bridges\ApplicationLatte\TemplateFactory(
			$this->getService('latte.latteFactory'),
			$this->getService('http.request')
		);
	}


	public function createServiceSession__session(): Nette\Http\Session
	{
		$service = new Nette\Http\Session($this->getService('http.request'), $this->getService('http.response'));
		$service->setOptions(['cookieSamesite' => 'Lax']);
		return $service;
	}


	public function createServiceTest__presenter(): App\TestPresenter
	{
		$service = new App\TestPresenter;
		$service->injectPrimary(
			$this,
			$this->getService('application.presenterFactory'),
			$this->getService('02'),
			$this->getService('http.request'),
			$this->getService('http.response'),
			$this->getService('session.session'),
			null,
			$this->getService('latte.templateFactory')
		);
		$service->visualPaginatorFactory = $this->getService('01');
		$service->invalidLinkMode = 1;
		return $service;
	}


	public function createServiceTracy__bar(): Tracy\Bar
	{
		return Tracy\Debugger::getBar();
	}


	public function createServiceTracy__blueScreen(): Tracy\BlueScreen
	{
		return Tracy\Debugger::getBlueScreen();
	}


	public function createServiceTracy__logger(): Tracy\ILogger
	{
		return Tracy\Debugger::getLogger();
	}


	public function initialize()
	{
		// tracy.
		(function () {
			if (!Tracy\Debugger::isEnabled()) { return; }
		})();
	}
}
