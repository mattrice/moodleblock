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
        $this->content->text = '<div class="navcontent">';

        // My Moodle icon 
        $this->content->text .= '<a class="tooltip" href="' . $CFG->wwwroot . '/" />
<span class="icon-stack icon-2x">
	<i class="icon-user icon-stack-base"></i>
</span>
<span class="text">My Moodle</span>
</a>';

        // Portal Icon 
        $this->content->text .= '<a class="tooltip" href="https://www.midmich.edu/portal" target="_blank" />
<span class="icon-stack icon-2x">
	<i class="icon-dashboard icon-stack-base"></i>
</span>
<span class="text">MMCC Portal</span>
</a>';

        // Message icon (check if it is disabled site wide then displays the icon accordingly)
        if (empty($CFG->messaging)) {
            // do not display icon
        } else {
            $this->content->text .= '<a class="tooltip" href="http://mail.google.com/a/midmich.edu" target="_blank" />
<span class="icon-stack icon-2x">
	<i class="icon-envelope icon-stack-base"></i>
</span>
<span class="text">MMCC Email</span>
</a>';
        }

        // Calendar icon 
        $this->content->text .= '<a class="tooltip" href="' . $CFG->wwwroot . '/calendar/view.php/" />
<span class="icon-stack icon-2x">
	<i class="icon-calendar icon-stack-base"></i>
</span>
<span class="text">My Calendar</span>
</a>';

        // Blog icons (check if it is disabled site wide then displays the icon accordingly)
        if (empty($CFG->bloglevel)) {
            // do not display blog icon
        } else {
            $this->content->text .= '<a class="tooltip" href="' . $CFG->wwwroot . '/blog/" />
<span class="icon-stack icon-2x">
	<i class="icon-comments-alt icon-stack-base"></i>
</span>
<span class="text">Blogs</span>
</a>';
            $this->content->text .= '<a class="tooltip" href="' . $CFG->wwwroot . '/blog/edit.php?action=add" />
<span class="icon-stack icon-2x">
	<i class="icon-edit-icon icon-stack-base"></i>
</span>
<span class="text">Add a blog entry</span>
</a>';
        }

        // Tag icon (check if it is disabled site wide then displays the icon accordingly) 
        if (empty($CFG->usetags)) {
            // do not display icon
        } else {
            $this->content->text .= '<a class="tooltip" href="' . $CFG->wwwroot . '/tag/" />
<span class="icon-stack icon-2x">
	<i class="icon-tags icon-stack-base"></i>
</span>
<span class="text">View all tags</span>
</a>';
        }

        // Admin Only Icons
        // get user rights
        $coursecontext = get_context_instance(CONTEXT_SYSTEM);   // SYSTEM context
        // check user is site admin
        if (has_capability('moodle/site:config', $coursecontext)) {

            // Admin Browse Users
            $this->content->text .= '<a class="tooltip" href="' . $CFG->wwwroot . '/admin/user.php" />
<span class="icon-stack icon-2x">
	<i class="icon-user icon-stack-base"></i>
	<i class="icon-pencil pull-right-down text-warning"></i>
</span>
<span class="text">Browse Users</span>
</a>';

            // Admin Add/Edit Courses 
            $this->content->text .= '<a class="tooltip" href="' . $CFG->wwwroot . '/course/index.php" />
<span class="icon-stack icon-2x">
	<i class="icon-th-list icon-stack-base"></i>
	<i class="icon-pencil pull-right-down text-warning"></i>
</span>
<span class="text">Add/Edit Courses</span>
</a>';

            // Admin Live Logs  (check if stats are enabled first!)
            if (empty($CFG->enablestats)) {
                // do not show stats icon if stats is disabled
            } else {
                $this->content->text .= '<a class="tooltip" href="' . $CFG->wwwroot . '/report/loglive/index.php?id=1&inpopup=1" />
<span class="icon-stack icon-2x">
	<i class="icon-bar-chart icon-stack-base"></i>
</span>
<span class="text">Live Logs</span>
</a>';
            }
        }

        $this->content->text .= '<a class="tooltip" href="javascript:toggleLayer(\'moodlebarcoursesearch\');" />
<span class="icon-stack icon-2x">
	<i class="icon-search icon-stack-base"></i>
</span>
<span class="text">Search Courses</span>
</a>';
        
        $this->content->text .= '</div>';

        // We don't want a footer
        $this->content->footer = "";

        return $this->content;
    }

}
