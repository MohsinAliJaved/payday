<?php


namespace App\Helpers\Route;


use App\Exceptions\GeneralException;

trait RouteToPermissionString
{
    protected array $exploded_route_name;

    protected string $route_name;

    public function validateRouteName()
    {
        $exploded_route_name = explode('.', $this->route_name);

        if (count($exploded_route_name) > 2) {
            throw new GeneralException('Route name can\'t be more than 2 index. Eg.brand.list.change not allowed');
        }

        $this->exploded_route_name = $exploded_route_name;

        return $this;
    }

    public function getPermissionString(): string
    {
        $exploded_route_name = $this->exploded_route_name;

        if (count($exploded_route_name) == 2) {
            $replacer_array = ['store' => 'create', 'index' => 'view', 'destroy' => 'delete', 'show' => 'view', 'lists' => 'view', 'edit' => 'update'];

            /*
             * if the index is exist in replacer array the replace the value with replacer array value
             * otherwise take the default value
            */
            $action = !empty($replacer_array[$exploded_route_name[1]]) ?
                $replacer_array[$exploded_route_name[1]] :
                $exploded_route_name[1];

            return str_replace('-', '_', $action . '_' . $exploded_route_name[0]);
        }

        $route_name = str_replace('-', '_', $this->route_name);

        return $permission_string = "manage_{$route_name}";
    }


    public function setRouteName(string $route_name)
    {
        $this->route_name = $route_name;
        return $this;
    }

    public function replaceToUnderScore()
    {
        foreach($this->exploded_route_name as $key => $route){
            $this->exploded_route_name[$key] = str_replace('-', '_', $route);
        }
    }
    private function calcRouteToPermissionString(){
        if($this->is_app && count($this->exploded_route_name) > 2){
            $this->replaceToUnderScore();

            $exploded_route_name = $this->exploded_route_name; 
            $replacer_array = $this->replace_array;
            $action = $exploded_route_name[count($exploded_route_name) -1];

            $action = !empty($replacer_array[$action]) ?
                        $replacer_array[$action] : $action;
            $exploded_route_name = array_reverse($exploded_route_name);
            $exploded_route_name[0] = $action;
            return join('_', $exploded_route_name);

        }

        if (count($this->exploded_route_name) > 2) {
            throw new GeneralException('Route name can\'t be more than 2 index. Eg.brand.list.change not allowed. Otherwise use route name as "app_permission." ');
        }

        $route_name = str_replace('-', '_', $this->route_name);

        return  "manage_{$route_name}";
    }
}