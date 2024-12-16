<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController
{
	/**
	 * @var mixed the default tooltip for every controller.
	 * if you give to this parameter a boolean false value instead of an array,
	 * the controller will not be displayed in the permission menagement view.
	 * for more information view the documentation in the userGroups module.
	 */
	public static $_permissionControl = array('read' => false, 'write' => false, 'admin' => false);
	/**
	 * @var string the default layout for the controller view. Defaults to '//layouts/column1',
	 * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
	 */
	public $layout='//layouts/column1';
	/**
	 * @var array context menu items. This property will be assigned to {@link CMenu::items}.
	 */
	public $menu=array();
	/**
	 * @var array the breadcrumbs of the current page. The value of this property will
	 * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
	 * for more details on how to specify this property.
	 */
	public $breadcrumbs=array();
	/**
	 * The filter method for 'UserGroupsAccessControl' filter.
	 * This filter is a wrapper of {@link UserGroupsAccessControl}.
	 * To use this filter, you must override {@link accessRules} method.
	 * @param CFilterChain $filterChain the filter chain that the filter is on.
	 */
	public function filterUserGroupsAccessControl($filterChain)
	{
		Yii::import('userGroups.models.UserGroupsUser');
		Yii::import('userGroups.models.UserGroupsConfiguration');
		Yii::import('userGroups.components.UserGroupsAccessControl');
		$filter=new UserGroupsAccessControl;
		$filter->setRules($this->accessRules());
		$filter->filter($filterChain);
	}

    public function init()
    {
        Yii::app()->extensionLoader->load();
        
        if($this->hasEventHandler('onBeforeInit'))
        {
            $event=new ControllerEvent($this);
            $this->onBeforeInit($event);
        }
        
        parent::init();        
        if($this->hasEventHandler('onAfterInit'))
        {
            $event=new ControllerEvent($this);
            $this->onAfterInit($event);
        }
    }
    
    public function onBeforeInit($event)
    {
			  	
        $this->raiseEvent('onBeforeInit', $event);
    }

    public function onAfterInit($event)
    {
        $this->raiseEvent('onAfterInit', $event);
    }

    public function onBeforeAction($event)
    {
		Yii::import('ext.LanguagePicker.ELanguagePicker'); 
		ELanguagePicker::setLanguage();
        $this->raiseEvent('onBeforeAction', $event);
        
    }

    public function onAfterAction($event)
    {
        $this->raiseEvent('onAfterAction', $event);
    }

    public function onBeforeRender($event)
    {
        $this->raiseEvent('onBeforeRender', $event);
    }

    public function onAfterRender($event)
    {
        $this->raiseEvent('onAfterRender', $event);
    }

    public function onBeforeProcessOutput($event)
    {
        $this->raiseEvent('onBeforeProcessOutput', $event);
    }

    public function onAfterProcessOutput($event)
    {
        $this->raiseEvent('onAfterProcessOutput', $event);
    }

    /**
     * This method is invoked right before an action is to be executed (after all possible filters.)
     * You may override this method to do last-minute preparation for the action.
     * @param CAction $action the action to be executed.
     * @return boolean whether the action should be executed.
     */
    protected function beforeAction($action)
    {
        if($this->hasEventHandler('onBeforeAction'))
        {
            $event=new ControllerEvent($this);
            $event->params['action']=$action;
            $this->onBeforeAction($event);
            return $event->isValid ? parent::beforeAction($action) : false;
        }
        return parent::beforeAction($action);
    }
    
    /**
     * This method is invoked right after an action is executed.
     * You may override this method to do some postprocessing for the action.
     * @param CAction $action the action just executed.
     */
    protected function afterAction($action)
    {
        parent::afterAction($action);
        if($this->hasEventHandler('onAfterAction'))
        {
            $event=new ControllerEvent($this);
            $event->params['action']=$action;
            $this->onAfterAction($event);
        }
    }


    
    /**
     * This method is invoked at the beginning of {@link render()}.
     * You may override this method to do some preprocessing when rendering a view.
     * @param string $view the view to be rendered
     * @return boolean whether the view should be rendered.
     */
    protected function beforeRender($view)
    {
        if($this->hasEventHandler('onBeforeRender'))
        {
            $event=new ControllerEvent($this);
            $event->params['view']=$view;
            $this->onBeforeRender($event);
            return $event->isValid ? parent::beforeRender($view) : false;
        }
        return parent::beforeRender($view);
    }
    
    /**
     * This method is invoked after the specified is rendered by calling {@link render()}.
     * Note that this method is invoked BEFORE {@link processOutput()}.
     * You may override this method to do some postprocessing for the view rendering.
     * @param string $view the view that has been rendered
     * @param string $output the rendering result of the view. Note that this parameter is passed
     * as a reference. That means you can modify it within this method.
     */
    protected function afterRender($view, &$output)
    {
        parent::afterRender($view, $output);
        
        if($this->hasEventHandler('onAfterRender'))
        {
            $event=new ControllerEvent($this);
            $event->params['view']=$view;
            $event->params['output']=&$output;
            $this->onAfterRender($event);
        }
    }
    
    /**
     * Postprocesses the output generated by {@link render()}.
     * This method is invoked at the end of {@link render()} and {@link renderText()}.
     * If there are registered client scripts, this method will insert them into the output
     * at appropriate places. If there are dynamic contents, they will also be inserted.
     * This method may also save the persistent page states in hidden fields of
     * stateful forms in the page.
     * @param string $output the output generated by the current action
     * @return string the output that has been processed.
     */
    public function processOutput($output)
    {
        if($this->hasEventHandler('onBeforeProcessOutput'))
        {
            $event=new ControllerEvent($this);
            $event->params['output']=&$output;
            $this->onBeforeProcessOutput($event);
        }

        $output=parent::processOutput($output);
        
        if($this->hasEventHandler('onAfterProcessOutput'))
        {
            $event=new ControllerEvent($this);
            $event->params['output']=&$output;
            $this->onAfterProcessOutput($event);
        }
        
        return $output;
    }

  
  //Nov30th: Added for Firebug Support. -WP.
  public function fb($debug){
   echo Yii::trace( CVarDumper::dumpAsString($debug), 'vardump' );
  }
	
}
