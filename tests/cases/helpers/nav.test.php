<?php

App::import('Helper', 'Nav');
App::import('Helper', 'Html');
App::import('Helper', 'Session');

class NavTest extends CakeTestCase {
    private $Nav = null;

    public function startTest() {
        $this->Nav = new NavHelper();
        $this->Nav->Html = new HtmlHelper();
        $this->Nav->Session = new SessionHelper();
    }

    public function testBuildAdminNavigation() {
        // check single dimension array. This will be a nav heading that 
        // is a link without children
        Configure::write('navigation.singleDimension', array(
            'link' => array('controller' => 'singles', 'action' => 'index'),
            'rel' => 'single',
            'title' => 'Single'
        ));

        $this->assertEqual('<li rel="single"><a href="/singles">Single</a></li>', $this->Nav->buildAdminNavigation('singleDimension'));

        // check nav header with children but no grandchildren
        Configure::write('navigation.noGrandChildren', array(
            'rel' => 'noGrandChildren',
            'title' => 'No Grand Children',
            'links' => array(
                array(
                    'link' => array('controller' => 'pages', 'action' => 'index'),
                    'rel' => 'pages',
                    'title' => 'Pages'
                )
            )
        ));

        $expected = '<li rel="noGrandChildren"><a>No Grand Children</a><ul><li rel="pages"><a href="/pages">Pages</a></li></ul></li>';
        $this->assertEqual($expected, $this->Nav->buildAdminNavigation('noGrandChildren'));

        // check nav header with children and grandchildren
        Configure::write('navigation.withGrandChildren', array(
            'rel' => 'withGrandChildren',
            'title' => 'With Grandchildren',
            'links' => array(
                array(
                    'rel' => 'news',
                    'title' => 'News',
                    'children' => array(
                        array(
                            'link' => array('controller' => 'press_releases', 'action' => 'index'),
                            'rel' => 'pressReleases',
                            'title' => 'Press Releases'
                        )
                    )
                )
            )
        ));

        $expected = '<li rel="withGrandChildren"><a>With Grandchildren</a>';
        $expected .= '<ul><li rel="news"><a>News</a><ul><li rel="pressReleases"><a href="/press_releases">Press Releases</a></li></ul></li></ul></li>';
        $this->assertEqual($expected, $this->Nav->buildAdminNavigation('withGrandChildren'));

        // check nav header with plugins as children
        Configure::write('navigation.hasPluginChildren', array(
            'rel' => 'hasPluginChildren',
            'title' => 'Has Plugin Children',
            'links' => array(
                array(
                    'link' => array('controller' => 'plugins', 'action' => 'index'),
                    'rel' => 'plugins',
                    'title' => 'Plugins'
                )
            )
        ));

        Configure::write('plugins.hasPluginChildren.nav_plugins', array(
            'rel' => 'plugin',
            'title' => 'Job Forms',
            'children' => array(
                array(
                    'link' => array('controller' => 'job_order_forms', 'action' => 'index'),
                    'rel' => 'plugin',
                    'title' => 'Job Order Forms'
                )
            )
        ));

        $expected = '<li rel="hasPluginChildren"><a>Has Plugin Children</a><ul><li rel="plugins"><a href="/plugins">Plugins</a></li>';
        $expected .= '<li rel="plugin"><a>Job Forms</a><ul><li rel="plugin"><a href="/job_order_forms">Job Order Forms</a></li></ul></li></ul></li>';
        $this->assertEqual($expected, $this->Nav->buildAdminNavigation('hasPluginChildren'));
    }
}
