<?php

namespace LWmvc\Controller\Controller;

class lwControllerCRUD extends \LWmvc\Controller\Controller
{
    public function __construct($cmd, $oid)
    {
        parent::__construct($cmd, $oid);
        $this->request = \lw_registry::getInstance()->getEntry("request");
    }
    
    public function setPackageName($packageName) 
    {
        $this->packageName = $packageName;
    }
    
    public function setModelName($modelName)
    {
        $this->modelName = $modelName;
    }
    
    public function setHookResolver($HookResolver)
    {
        $this->HookResolver = $HookResolver;
    }
    
    public function setAuthorityKeeper($keeper)
    {
        $hthis->AuthorityKeeper = $keeper;
    }
    
    public function setError($error)
    {
        $this->error = $error;
    }
    
    public function execute()
    {
        $method = $this->getCommand()."Action";
        if (method_exists($this, $method)) {
            return $this->$method();
        }
        else {
            throw new \LWmvc\Model\ControllerMethodNotFoundException($this->getCommand());
        }
    }
    
    public function showListAction()
    {
        if (method_exists($this->HookResolver, "PreShowListAction")) {
            $Response = $this->HookResolver->PreShowListAction();
            if ($Response->getParameterByKey("break") == 1) {
                return $Response;
            }
        }
        
        $Response = \LWmvc\Model\CommandDispatch::getInstance()->execute($this->packageName, $this->modelName, 'lwCommandLoadAllEntitiesCollection');
        $Collection = $Response->getDataByKey("allEntitiesCollection");
        
        $viewname = "\\".$this->packageName."\View\ListView";
        $view = new $viewname();
        $view->setCollection($Collection);
        return $this->returnRenderedView($view);
    }
    
    public function AddFormAction()
    {
        if (method_exists($this->HookResolver, "PreAddFormAction")) {
            $Response = $this->HookResolver->PreAddFormAction();
            if ($Response->getParameterByKey("break") == 1) {
                return $Response;
            }
        }
        $formname = "\\".$this->packageName."\View\FormView";
        $formView = new $formname();
        $formView->init("add");

        if (method_exists($this->HookResolver, "modifyFormViewAction")) {
            $Response = $this->HookResolver->modifyFormViewAction($formView);
            if ($Response->getParameterByKey("break") == 1) {
                return $Response;
            }
        }        
        
        $response = \LWmvc\Model\CommandDispatch::getInstance()->execute($this->packageName, $this->modelName, 'lwCommandGetEntryFromPostArray', array(), array("postArray"=>$this->request->getPostArray()));
        $formView->setEntity($response->getDataByKey('Entity'));
        
        $formView->setErrors($this->error);
        return $this->returnRenderedView($formView);
    }
    
    public function AddAction()
    {
        if (method_exists($this->HookResolver, "PreAddAction")) {
            $Response = $this->HookResolver->PreAddAction();
            if ($Response->getParameterByKey("break") == 1) {
                return $Response;
            }
        }

        $response = \LWmvc\Model\CommandDispatch::getInstance()->execute($this->packageName, $this->modelName, 'lwCommandAdd', false, array('postArray'=>$this->request->getPostArray()));

        if (method_exists($this->HookResolver, "PostAddAction")) {
            $Response = $this->HookResolver->PostAddAction($response);
            if ($Response->getParameterByKey("break") == 1) {
                return $Response;
            }
        }
        
        if ($response->getParameterByKey("error")) {
            $this->setError($response->getDataByKey("error"));
            return $this->AddFormAction();
        }
        return $this->buildReloadResponse(array("cmd"=>"showList", "response"=>1));
    }
    
    public function EditFormAction()
    {
        if (method_exists($this->HookResolver, "PreEditFormAction")) {
            $Response = $this->HookResolver->PreEditFormAction();
            if ($Response->getParameterByKey("break") == 1) {
                return $Response;
            }
        }        
        $formname = "\\".$this->packageName."\View\FormView";
        $formView = new $formname();
        $formView->init("edit");

        if (method_exists($this->HookResolver, "modifyFormViewAction")) {
            $Response = $this->HookResolver->modifyFormViewAction($formView);
            if ($Response->getParameterByKey("break") == 1) {
                return $Response;
            }
        }        
        
        if ($this->error) {
            $response = \LWmvc\Model\CommandDispatch::getInstance()->execute($this->packageName, $this->modelName, 'lwCommandGetEntryFromPostArray', array(), array("postArray"=>$this->request->getPostArray()));
        }
        else {
            $response = \LWmvc\Model\CommandDispatch::getInstance()->execute($this->packageName, $this->modelName, 'lwCommandGetEntryById', array("decorated"=>true, "id"=>$this->request->getInt("id")));
        }                
        $entity = $response->getDataByKey('Entity');
        $entity->setId($this->request->getInt("id"));
        $formView->setEntity($entity);

        $response = \LWmvc\Model\CommandDispatch::getInstance()->execute($this->packageName, $this->modelName, 'lwCommandGetSpecification', array('SpecificationName'=>'isDeletable'));
        $formView->setIsDeletableSpecification($response->getDataByKey('Specification'));
        
        $formView->setErrors($this->error);
        return $this->returnRenderedView($formView);
    }
    
    public function saveAction()
    {
        if (method_exists($this->HookResolver, "PreSaveAction")) {
            $Response = $this->HookResolver->PreSaveAction();
            if ($Response->getParameterByKey("break") == 1) {
                return $Response;
            }
        }        
        $response = \LWmvc\Model\CommandDispatch::getInstance()->execute(
             $this->packageName,
             $this->modelName, 
             'lwCommandSave', 
             array("id"=>$this->request->getInt("id"), "configuration"=>$this->config), 
             array('postArray'=>$this->request->getPostArray())
        );
        
        if (method_exists($this->HookResolver, "PostSaveAction")) {
            $Response = $this->HookResolver->PostSaveAction($response);
            if ($Response->getParameterByKey("break") == 1) {
                return $Response;
            }
        }
        
        if ($response->getParameterByKey("error")) {
            $this->setError($response->getDataByKey("error"));
            return $this->EditFormAction();
        }
        return $this->buildReloadResponse(array("cmd"=>"EditForm", "id"=>$this->request->getInt("id"), "response"=>1));
    }
    
    public function deleteAction()
    {
        if (method_exists($this->HookResolver, "PreDeleteAction")) {
            $Response = $this->HookResolver->PreDeleteAction();
            if ($Response->getParameterByKey("break") == 1) {
                return $Response;
            }
        }        
        
        $response = \LWmvc\Model\CommandDispatch::getInstance()->execute($this->packageName, $this->modelName, 'lwCommandGetSpecification', array('SpecificationName'=>'isDeletable'));
        $isDeletableSpecification = $response->getDataByKey('Specification');

        $response = \LWmvc\Model\CommandDispatch::getInstance()->execute($this->packageName, $this->modelName, 'lwCommandDelete', array("id"=>$this->request->getInt("id"), "isDeletableSpecification"=>$isDeletableSpecification));
        
        if (method_exists($this->HookResolver, "PostDeleteAction")) {
            $Response = $this->HookResolver->PostDeleteAction($response);
            if ($Response->getParameterByKey("break") == 1) {
                return $Response;
            }
        }
        
        return $this->buildReloadResponse(array("cmd"=>"showList", "response"=>2));        
    }
}