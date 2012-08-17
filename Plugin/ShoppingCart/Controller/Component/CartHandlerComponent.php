<?php

App::uses('Component', 'Controller');

/**
 * Component with some usefull function from cart
 *
 * @package Shopping Cart
 * @author Thiago Colares
 */
class CartHandlerComponent extends Component {
	/**
	 * Components
	 *
	 * @var array $components
	 */
	public $components = array('Session');

	/**
	 * Model primary key. Can be overrided by settings
	 *
	 * @var string Model name
	 */
	public $primaryKey = 'id';
	
	/**
	 * Name of product model
	 *
	 * @var string Model name
	 */
	public $modelName = null;
	
	/**
	 * Name of product plugin
	 *
	 * @var string Model name
	 */
	public $pluginName = null;
	
	/**
	 * Controller
	 *
	 * @var mixed $controller
	 */
	public $Controller = null;
		
	/**
	 * Constructor. Constructor for the base component class.
	 * All $settings that are also public properties will have their values
	 * changed to the matching value in $settings.
	 *
	 * @param ComponentCollection $collcetion
	 * @param array $settings
	 * @return void
	 */
	public function __construct(ComponentCollection $collection, $settings = array()) {
		parent::__construct($collection, $settings); 
		foreach ($settings as $setting => $value) {
			if (isset($this->{$setting})) {
				$this->{$setting} = $value;
			}
		}
	}
	
	/**
	 * Initialize Callback
	 * The initialize method is called before the controller’s beforeFilter method.
	 *
	 * @param object
	 * @return void
	 */
	public function initialize(Controller $controller) {
		$this->Controller = $controller;
		if(!$this->Session->check('CartProduct')) {
			$this->Session->write('CartProduct', array());
		}
		// if (empty($this->Cookie) && !empty($this->Controller->Cookie)) {
		// 		$this->Cookie = $this->Controller->Cookie;
		// 	}
		// 	if (empty($this->Session) && !empty($this->Controller->Session)) {
		// 		$this->Session = $this->Controller->Session;
		// 	}
		// 	if (empty($this->Auth) && !empty($this->Controller->Auth)) {
		// 		$this->Auth = $this->Controller->Auth;
		// 	}
		// 
		// 	$this->modelName = $controller->modelClass;
		// 	$this->modelAlias = $controller->{$this->modelName}->alias;
		// 	$this->viewVariable = Inflector::variable($this->modelName);
		// 	$controller->helpers = array_merge($controller->helpers, array('Comments.CommentWidget', 'Time', 'Comments.Cleaner', 'Comments.Tree'));
		// 
		// 	if (!$controller->{$this->modelName}->Behaviors->attached('Commentable')) {
		// 		$controller->{$this->modelName}->Behaviors->attach('Comments.Commentable', array('userModelAlias' => $this->userModel, 'userModelClass' => $this->userModelClass));
		// 	}
	}
	
	
	/**
	 * Startup Method
	 *
	 * LoadAutomatically makes cart for all a the controllers based on the current user.
	 * If $this->autoLoad = false then you must manually loadCache(), 
	 * contstructMenu() and writeCache().
	 *
	 * @param Object $Controller
	 */
	// public function startup(&$Controller) {
	// 	$this->Controller =& $Controller;
	// 
	// 	//Cache::config($this->cacheConfig, array('engine' => 'File', 'duration' => $this->cacheTime, 'prefix' => $this->cacheKey));
	// 
	// 	//no active session, no menu can be generated
	// 	// if (!$this->Auth->user()) {
	// 	// 	return;
	// 	// }
	// 	// if ($this->autoLoad) {
	// 	// 	$this->loadCache();
	// 	// 	$this->constructMenu($this->Auth->user());
	// 	// 	$this->writeCache();
	// 	// }
	// }
    public function addToCart() {
		/*
			TODO IF MULTIPLE ITEMS!
		*/
		$newCartProduct = array("$this->modelName" => array(
			'id' => $this->Controller->request->data[$this->modelName][$this->primaryKey],
			'model' => $this->modelName,
			'plugin' => $this->pluginName
		));
		
		$cartProducts = $this->Session->read('CartProduct');
		$cartProducts = array_merge($cartProducts, $newCartProduct);
		
		return array('success' => true);
		
    }

	/**
	 * Count how many items Cart have
	 *
	 * @return void
	 * @author Thiago Colares
	 */
    public function countCart() {
		return count($this->Session->read('CartProduct'));
    }
	
}
?>