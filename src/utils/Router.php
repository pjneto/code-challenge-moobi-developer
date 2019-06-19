<?php

$url = $_SERVER['REQUEST_URI'];
if (strpos($url, ".php")){
    header("Location: 404");
    exit;
}

class Router {
    private $routers;

    function __construct(){

        $this->routers = [];

        $this->set_route("", "HomeView");
        $this->set_route("index", "HomeView");
        $this->set_route("home", "HomeView");

        $this->set_route("produto", "ProductView", array(
            "novo" => "ProductNewView",
            "detalhes" => "ProductDetailView",
        ));

        $this->set_route("pedido", "OrderView", array(
            "finalizar" => "OrderFinishView",
            "novo" => "OrderNewView",
            "detalhes" => "OrderDetailView",
        ));
    }

    public function route(){
        $size = intval(sizeof($_GET));
        if ($size === 0):
            $this->include("HomeView");
        elseif ($size === 1):
            $view = $this->get_view($_GET['section']);
            $this->include($view);
        elseif ($size === 2 || $size === 3):
            $section = $_GET['section'];
            $actions = $this->get_actions($section);
            if (sizeof($actions) > 0):
                $view = $this->get_action($_GET['action'], $actions);
                $this->include($view);
            else:
                $this->include("PageNotFoundView");
            endif;
        elseif ($size > 3):
            $this->include("PageNotFoundView");
        endif;
    }

    private function set_route(string $key, string $view, array $actions = null){
        $actions = is_null($actions) ? array() : $actions;
        $this->routers[$key] = array(
            "view" => $view, 
            "actions" => array_merge(array("" => $view), $actions),
        );
    }

    private function get_view($route): string {
        return array_key_exists($route, $this->routers)
            ? $this->routers[$route]['view']
            : "PageNotFoundView";
    }

    private function get_actions($route): array { 
        $view = $this->get_view($route);
        return $view === "PageNotFoundView" ? array() 
            : $this->routers[$route]['actions'];
    }

    private function get_action($action, $actions): string {
        return array_key_exists($action, $actions) 
            ? $actions[$action] : "PageNotFoundView";
    }

    private function include($route) {
        $page = "views/" . $route . ".php"; 
        $page = file_exists($page) ? $page : 'views/PageNotFoundView.php';
        include_once $page;
    } 
}?>