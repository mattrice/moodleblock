<?php

// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/*
 * A block which allows for quick navigation to certain areas.
 * Based off of the moodlebar plug-in, by Lewis Carr.
 * https://tracker.moodle.org/browse/CONTRIB-1797
 * 
 * @package     block_moodleblock
 * @copyright   2013 Matt Rice
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

class block_moodleblock extends block_base {

    public function init() {
        $this->title = get_string('moodleblock', 'block_moodleblock');
    }

    public function get_content() {
        if ($this->content !== null) {
            return $this->content;
        }

        global $CFG, $OUTPUT;
        
        $this->content = new stdClass;
        $this->content->text = "";

        // My Moodle icon 
        //  icon-user
        $this->content->text .= '<a class="tooltip" href="' . $CFG->wwwroot . '/" /><i class="icon-user" title="My Moodle"></i></a>';

        // Portal Icon 
        //  icon-dashboard
        $this->content->text .= '<a class="tooltip" href="https://www.midmich.edu/portal" target="_blank" /><img src="' . $OUTPUT->pix_url('moodlebar/profile', 'theme') . '" title="MMCC Portal"  /><span>MMCC Portal</span></a>';

        // Message icon (check if it is disabled site wide then displays the icon accordingly)
        //  icon-envelope
        if (empty($CFG->messaging)) {
            // do not display icon
        } else {
            $this->content->text .= '<a class="tooltip" href="http://mail.google.com/a/midmich.edu" target="_blank" /><img src="' . $OUTPUT->pix_url('moodlebar/email', 'theme') . '" title="MidMail"  /><span>MidMail</span></a>';
        }

        // Calendar icon 
        //  icon-calendar
        $this->content->text .= '<a class="tooltip" href="' . $CFG->wwwroot . '/calendar/view.php/" /><img src="' . $OUTPUT->pix_url('moodlebar/calendar', 'theme') . '" title="My Calendar"  /><span>My Calendar</span></a>';

        // Blog icons (check if it is disabled site wide then displays the icon accordingly)
        //  icon-comments-alt
        //  icon-edit-sign
        if (empty($CFG->bloglevel)) {
            // do not display blog icon
        } else {
            $this->content->text .= '<a class="tooltip" href="' . $CFG->wwwroot . '/blog/" /><img src="' . $OUTPUT->pix_url('moodlebar/blog', 'theme') . '" title="Blogs"  /><span>Blogs</span></a>';
            $this->content->text .= '<a class="tooltip" href="' . $CFG->wwwroot . '/blog/edit.php?action=add" /><img src="' . $OUTPUT->pix_url('moodlebar/blogadd', 'theme') . '" title="Add a blog entry"  /><span>Add a blog entry</span></a>';
        }

        // Tag icon (check if it is disabled site wide then displays the icon accordingly) 
        //  icon-tags
        if (empty($CFG->usetags)) {
            // do not display icon
        } else {
            $this->content->text .= '<a class="tooltip" href="' . $CFG->wwwroot . '/tag/" /><img src="' . $OUTPUT->pix_url('moodlebar/tags', 'theme') . '" title="Tags"  /><span>View all tags</span></a>';
        }

        // Admin Only Icons
        // get user rights
        $coursecontext = get_context_instance(CONTEXT_SYSTEM);   // SYSTEM context
        // check user is site admin
        if (has_capability('moodle/site:config', $coursecontext)) {

            // Admin Browse Users
            //  icon-user
            //  icon-search
            $this->content->text .= '<a class="tooltip" href="' . $CFG->wwwroot . '/admin/user.php" />
<span class="icon-stack icon-2x">
	<i class="icon-user icon-stack-base"></i>
	<i class="icon-pencil pull-right-down text-warning"></i>
</span>
<span class="text">Browse Users</span>
</a>';

            // Admin Add/Edit Courses 
            //  icon-sitemap
            //  icon-pencil
            $this->content->text .= '<a class="tooltip" href="' . $CFG->wwwroot . '/course/index.php" /><img src="' . $OUTPUT->pix_url('moodlebar/addcourses', 'theme') . '" title="Add/Edit Courses"  /><span>Add/Edit Courses</span></a>';

            // Admin Live Logs  (check if stats are enabled first!)
            //  icon-bar-chart
            if (empty($CFG->enablestats)) {
                // do not show stats icon if stats is disabled
            } else {
                $this->content->text .= '<a class="tooltip" href="' . $CFG->wwwroot . '/report/loglive/index.php?id=1&inpopup=1" /><img src="' . $OUTPUT->pix_url('moodlebar/graph', 'theme') . '" title="Live Logs"  /><span>Live Logs</span></a>';
            }
        }

        //  icon-site-map
        //  icon-search
        $this->content->text .= '<a class="tooltip" href="javascript:toggleLayer(\'moodlebarcoursesearch\');" /><img src="' . $OUTPUT->pix_url('moodlebar/search', 'theme') . '" title="Search Courses"  /><span>Search Courses</span></a>';

        // We don't want a footer
        $this->content->footer = "";

        return $this->content;
    }

}
