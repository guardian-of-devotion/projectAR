<?php


namespace leantime\domain\controllers;

use leantime\core;
use leantime\domain\repositories;
use leantime\domain\services;
use leantime\domain\models;

class notes
{

    private $tpl;
    private $projects;


    /**
     * constructor - initialize private variables
     *
     * @access public
     * @params parameters or body of the request
     */
    public function __construct()
    {

        $this->tpl = new core\template();
        $this->projects = new repositories\projects();
        $this->notesService = new services\notes();

    }


    /**
     * get - handle get requests
     *
     * @access public
     * @params parameters or body of the request
     */
    public function get($params)
    {

    }

    /**
     * post - handle post requests
     *
     * @access public
     * @params parameters or body of the request
     */
//    public function post($params)
//    {
//
//        if(isset($params['action']) && $params['action'] == "kanbanSort" && isset($params["payload"]) === true){
//
//            $handler = null;
//            if(isset($params["handler"]) == true){
//                $handler = $params["handler"];
//            }
//            $results = $this->ticketsApiService->updateTicketStatusAndSorting($params["payload"], $handler);
//
//            if($results === true) {
//
//                echo "{status:ok}";
//
//            }else{
//
//                echo "{status:failure}";
//
//            }
//
//        }else{
//
//            echo "{status:failure}";
//
//        }
//
//    }

    /**
     * put - handle put requests
     *
     * @access public
     * @params parameters or body of the request
     */
    public function patch($params)
    {
        $results = $this->notesService->patchNote($params['id'], $params);

        if($results === true) {
            echo "{status:ok}";
        }else{
            echo "{status:failure}";
        }
    }
}