<?php

namespace App\Http\View\Composers;

use Illuminate\Http\Response;
use Illuminate\View\View;
use App\Main\TopMenu;
use App\Main\SideMenu;
use App\Main\SimpleMenu;

class MenuComposer
{
    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view): void
    {
        if (!is_null(request()->route())) {
            $pageName = request()->route()->getName();
            $layout = $this->layout($view);
            $activeMenu = $this->activeMenu($pageName, $layout);

            $view->with('top_menu', TopMenu::menu());
            $view->with('side_menu', SideMenu::menu());
            $view->with('simple_menu', SimpleMenu::menu());
            $view->with('first_level_active_index', $activeMenu['first_level_active_index']);
            $view->with('second_level_active_index', $activeMenu['second_level_active_index']);
            $view->with('third_level_active_index', $activeMenu['third_level_active_index']);
            $view->with('page_name', $pageName);
            $view->with('layout', $layout);
        }
    }

    /**
     * Specify used layout.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return Response
     */
    public function layout($view): Response|string
    {
        if (isset($view->layout)) {
            return $view->layout;
        } else if (request()->has('layout')) {
            return request()->query('layout');
        }

        return 'side-menu';
    }

    /**
     * Determine active menu & submenu.
     *
     * @param $pageName
     * @param $layout
     * @return array
     */
    public function activeMenu($pageName, $layout): array
    {
        $firstLevelActiveIndex = '';
        $secondLevelActiveIndex = '';
        $thirdLevelActiveIndex = '';

        if ($layout == 'top-menu') {
            $subMenus = $this->_renderSubMenu(TopMenu::menu(),  $pageName, $firstLevelActiveIndex, $secondLevelActiveIndex, $thirdLevelActiveIndex);
        } else if ($layout == 'simple-menu') {
            $subMenus = $this->_renderSubMenu(SimpleMenu::menu(),  $pageName, $firstLevelActiveIndex, $secondLevelActiveIndex, $thirdLevelActiveIndex);
        } else {
            $subMenus = $this->_renderSubMenu(SideMenu::menu(),  $pageName, $firstLevelActiveIndex, $secondLevelActiveIndex, $thirdLevelActiveIndex);
        }

        return [
            'first_level_active_index' => $subMenus['first_level_active_index'],
            'second_level_active_index' => $subMenus['second_level_active_index'],
            'third_level_active_index' => $subMenus['third_level_active_index'],
        ];
    }

    private function _renderSubMenu(array $menus, $pageName,  $firstLevelActiveIndex, $secondLevelActiveIndex, $thirdLevelActiveIndex): array
    {
        foreach ($menus as $menuKey => $menu) {
            if ($menu !== 'devider' && isset($menu['route_name']) && $menu['route_name'] == $pageName && empty($firstPageName)) {
                $firstLevelActiveIndex = $menuKey;
            }

            if (isset($menu['sub_menu'])) {
                foreach ($menu['sub_menu'] as $subMenuKey => $subMenu) {
                    if (isset($subMenu['route_name']) && $subMenu['route_name'] == $pageName && $menuKey != 'menu-layout' && empty($secondPageName)) {
                        $firstLevelActiveIndex = $menuKey;
                        $secondLevelActiveIndex = $subMenuKey;
                    }

                    if (isset($subMenu['sub_menu'])) {
                        foreach ($subMenu['sub_menu'] as $lastSubMenuKey => $lastSubMenu) {
                            if (isset($lastSubMenu['route_name']) && $lastSubMenu['route_name'] == $pageName) {
                                $firstLevelActiveIndex = $menuKey;
                                $secondLevelActiveIndex = $subMenuKey;
                                $thirdLevelActiveIndex = $lastSubMenuKey;
                            }
                        }
                    }
                }
            }
        }

        return [
            'first_level_active_index' => $firstLevelActiveIndex,
            'second_level_active_index' => $secondLevelActiveIndex,
            'third_level_active_index' => $thirdLevelActiveIndex
        ];
    }
}
