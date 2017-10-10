<?php
namespace Pure\Bases;
use Pure\Exceptions\ViewException;
use Pure\Utils\Session;
use Pure\Utils\Params;

/**
 * Classe básica para Controllers
 *
 * Deve servir como base para outros controllers, é
 * dotado de capacidade de acesso a camada Model e responsável
 * por renderizar a camada View
 *
 * @abstract
 * @version 1.0
 * @author Equipe Projeto X
 */
abstract class Controller
{
	protected $data = [];
	protected $page;
	protected $layout;
	protected $session;
	protected $params;

	public function __construct()
	{
		$this->session = Session::get_instance();
		$this->params = Params::get_instance();
	}

	/**
	 * Método que será executado antes do método action
	 */
	public function before()
	{
	}

	/**
	 * Método que será executado depois do método action
	 */
	public function after()
	{
	}

	/**
	 * Método de carregamento da View
	 * No PurePHP a view é um conjunto de scripts incluidos no contexto da action,
	 * ao chamar o método render todas itens do array $this->data passam a ser variaveis
	 * locais no contexto da View.
	 *
	 * A view pode ser divida em três partes:
	 * - Layout: entorno da página, renderizado ao chamar o método $this->render()
	 * - Page: conteúdo individual de cada página renderizado a partir de $this->content()
	 * - Component: trecho de código php inserido na view pelo método $this->render_component($name, $data =[])
	 *
	 * Pode receber o nome do arquivo de página e de layout, respecitivamente nas pastas pages e layouts
	 * caso não receba, utiliza os valores padrões 'index' e 'default'
	 *
	 * @param string $page valor padrão 'index', referencia script em /app/views/pages/index.php
	 * @param string $layout valor padrão 'default', referencia script em /app/views/layouts/default.php
	 * @throws ViewException caso não seja possivel carregar o layout
	 */
	public function render($page = 'index', $layout = 'default')
	{
		$this->layout = BASE_PATH . 'app/views/layouts/' . $layout . '.php';
		$this->page = BASE_PATH . 'app/views/pages/' . $page . '.php';
		foreach($this->data as $name => $item)
		{
			$$name = $item;
		}
		if((@include($this->layout)) === false)
		{
			throw new ViewException($this->layout . ' não foi encontrado.');
		}
	}

	/**
	 * Renderiza conteúdo AJAX, sem cabeçalho HTTP
	 * @param string $page valor padrão 'index', referencia script em /app/views/pages/ajax/index.php
	 * @throws ViewException caso não seja possivel carregar o layout
	 */
	public function render_ajax($page = 'index')
	{
		$this->page = BASE_PATH . 'app/views/pages/ajax/' . $page . '.php';
		foreach($this->data as $name => $item)
		{
			$$name = $item;
		}
		if((@include($this->page)) === false)
		{
			throw new ViewException($this->page . ' não foi encontrado.');
		}
	}

	/**
	 * Renderiza em JSON o conteúdo da página
	 */
	public function render_json()
	{
		echo json_encode($this->data);
	}

	/**
	 * Método que carrega componente no contexto da View
	 * Utilizado para carregar trecho de código php dentro do contexto da View.
	 * Deve receber o nome do arquivo presente na pasta /app/views/components/.
	 *
	 * @see render
	 * @param mixed $name nome do componente, referencia script em /app/views/components/...
	 * @param mixed $data variaveis que estarão presentes no contexto do component
	 * @throws ViewException caso não seja possivel carregar o component
	 */
	public function render_component($name, $data = [])
	{
		$module = BASE_PATH . 'app/views/components/' . $name . '.php';
		foreach($data as $name => $item)
		{
			$$name = $item;
		}
		if((@include($module)) === false)
		{
			throw new ViewException($module . ' não foi encontrado.');
		}
	}

	/**
	 * Método de carregamento de conteúdo na View
	 * Utilizado para carregar trecho de código php dentro do contexto da View
	 * escolhido no método render()
	 * Diferente do component, só se pode renderizar um por vez e as varivaveis
	 * presentes em $this->data estão presentes assim como em layout.
	 *
	 * @see render()
	 * @throws ViewException caso não seja possivel carregar a pagina
	 */
	private function content()
	{
		foreach($this->data as $name => $item)
		{
			$$name = $item;
		}
		if((include($this->page)) === false)
		{
			throw new ViewException($this->page . 'não foi encontrado.');
		}
	}

}