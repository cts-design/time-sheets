<?php
/**
 * @author Brandon Cordell
 * @copyright Complete Technology Solutions 2010
 * @link http://ctsfla.com
 * @package ATLAS V3
 */
class Navigation extends AppModel {
	var $name = 'Navigation';
	var $displayField = 'title';
        var $actsAs = array('Tree');
        var $order = 'Navigation.lft ASC';

        /**
         * Finds the parent node using position as the title, then retreives all children
         *
         * @param string $position the position on the navigation on the page. translates to the title of the parent node
         * @return array $children returns all the children of the parent node
         */
        function findChildrenByPosition($position) {
            $parent = $this->find('first', array('conditions' => array('title' => $position)));
            $parent_id = $parent['Navigation']['id'];
            $children = $this->find('list', array('conditions' => array('parent_id' => $parent_id),
                                                        'fields' => array('Navigation.title', 'Navigation.link')));

            return $children;
        }

        function populateDb() {
            $this->create(array('title' => 'Top'));
            $this->save();

                $parent_id = $this->id;

                $this->create(array('parent_id' => $parent_id, 'title' => 'Home', 'link' => '/'));
                $this->save();

                $this->create(array('parent_id' => $parent_id, 'title' => 'Career Fairs', 'link' => '/career_fairs'));
                $this->save();

                $this->create(array('parent_id' => $parent_id, 'title' => 'Networking', 'link' => '/networking'));
                $this->save();

                $this->create(array('parent_id' => $parent_id, 'title' => 'Featured Employers', 'link' => '/featured_employers'));
                $this->save();

                $this->create(array('parent_id' => $parent_id, 'title' => 'Locations', 'link' => '/locations'));
                $this->save();

                $this->create(array('parent_id' => $parent_id, 'title' => 'Calendar of Events', 'link' => '/calendar_of_events'));
                $this->save();

            $this->create(array('title' => 'Left'));
            $this->save();

                $parent_id = $this->id;

                $this->create(array('parent_id' => $parent_id, 'title' => 'About Us', 'link' => '/about_us'));
                $this->save();

                $this->create(array('parent_id' => $parent_id, 'title' => 'Employers', 'link' => '/employers'));
                $this->save();

                $this->create(array('parent_id' => $parent_id, 'title' => 'Career Seekers', 'link' => '/career_seekers'));
                $this->save();

                $this->create(array('parent_id' => $parent_id, 'title' => 'Specialty Programs', 'link' => '/specialty_programs'));
                $this->save();

                $this->create(array('parent_id' => $parent_id, 'title' => 'Youth Programs', 'link' => '/youth_programs'));
                $this->save();

                $this->create(array('parent_id' => $parent_id, 'title' => 'In the News', 'link' => '/in_the_news'));
                $this->save();

                $this->create(array('parent_id' => $parent_id, 'title' => 'Unemployment Claims', 'link' => '/unemployment_claims'));
                $this->save();

                $this->create(array('parent_id' => $parent_id, 'title' => 'Employers Survey', 'link' => '/employers_survey'));
                $this->save();

                $this->create(array('parent_id' => $parent_id, 'title' => 'Career Seekers Survey', 'link' => '/career_seekers_survey'));
                $this->save();

            $this->create(array('name' => 'Bottom'));
            $this->save();

                $parent_id = $this->id;

                $this->create(array('parent_id' => $parent_id, 'title' => 'Contact Us', 'link' => '/contact_us'));
                $this->save();

                $this->create(array('parent_id' => $parent_id, 'title' => 'Terms of Use', 'link' => '/terms_of_use'));
                $this->save();
        }
}
?>