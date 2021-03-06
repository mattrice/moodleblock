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

        global $CFG, $DB, $USER;
        
        $this->content = new stdClass;
        $this->content->text = '<div class="navcontent">';

        // My Moodle icon 
        $this->content->text .= '<a class="tooltip" href="' . $CFG->wwwroot . '/my" />
<span class="fa-stack fa-2x">
	<i class="fa fa-user fa-stack-2x fa-fw"></i>
</span>
<span class="text">'. get_string('mymoodle', 'block_moodleblock') .'</span>
</a>';

        // Portal Icon 
         $this->content->text .= '<a class="tooltip" href="https://www.midmich.edu/portal" target="_blank" />
<span class="fa-stack fa-2x">
	<i class="fa fa-tachometer fa-stack-2x fa-fw"></i>
</span>
<span class="text">'. get_string('portal', 'block_moodleblock') .'</span>
</a>';
       
        //Email icon
        $this->content->text .= '<a class="tooltip" href="http://mail.google.com/a/midmich.edu" target="_blank" />
<span class="fa-stack fa-2x">
	<i class="fa fa-envelope fa-stack-2x fa-fw"></i>
</span>
<span class="text">'. get_string('email', 'block_moodleblock') .'</span>
</a>';
        
        // Message icon (check if it is disabled site wide then displays the icon accordingly)
        if (empty($CFG->messaging)) {
            // do not display icon
        } else {
            $messagecount = $DB->count_records('message', array('useridto'=>$USER->id));
            $this->content->text .= '<a class="tooltip" href="'.$CFG->wwwroot.'/message/index.php" target="_blank" />
<span class="fa-stack fa-2x">
	<i class="fa fa-comments-o fa-stack-2x fa-fw"></i>';
            // Indicate new messages
            if (0 < $messagecount) {
                //User has new messages
                $this->content->text .= '
	<i class="fa fa-exclamation-circle fa-stack-1x text-error pull-right-down"></i>
</span>
<span class="text">' . get_string(((1==$messagecount)?'newmessage':'newmessages'), 'block_moodleblock', $messagecount) . '</span>
</a>';
            } else {
                //No new messages
            $this->content->text .= '
</span>
<span class="text">' . get_string('nonewmessages', 'block_moodleblock') . '</span>
</a>';
            }
        }

        // Calendar icon 
        $this->content->text .= '<a class="tooltip" href="' . $CFG->wwwroot . '/calendar/view.php/" />
<span class="fa-stack fa-2x">
	<i class="fa fa-calendar fa-stack-2x fa-fw"></i>
</span>
<span class="text">'. get_string('mycalendar', 'block_moodleblock') .'</span>
</a>';

        // Blog icons (check if it is disabled site wide then displays the icon accordingly)
	//Users fa-group (not fa-users like is shown in the FA cheatsheet)
        if (empty($CFG->bloglevel)) {
            // do not display blog icon
        } else {
            $this->content->text .= '<a class="tooltip" href="' . $CFG->wwwroot . '/blog/" />
<span class="fa-stack fa-2x">
	<i class="fa fa-files-o fa-stack-2x fa-fw"></i>
</span>
<span class="text">'. get_string('browseblogs', 'block_moodleblock') .'</span>
</a>';

            $this->content->text .= '<a class="tooltip" href="' . $CFG->wwwroot . '/blog/edit.php?action=add" />
<span class="fa-stack fa-2x">
	<i class="fa fa-pencil-square-o fa-stack-2x fa-fw"></i>
</span>
<span class="text">'. get_string('addblogentry', 'block_moodleblock') .'</span>
</a>';
        }

        // Tag icon (check if it is disabled site wide then displays the icon accordingly) 
        if (empty($CFG->usetags)) {
            // do not display icon
        } else {
            $this->content->text .= '<a class="tooltip" href="' . $CFG->wwwroot . '/tag/" />
<span class="fa-stack fa-2x">
	<i class="fa fa-tags fa-stack-2x fa-fw"></i>
</span>
<span class="text">'. get_string('viewtags', 'block_moodleblock') .'</span>
</a>';
        }
        

        // Admin Only Icons
        // get user rights
        $coursecontext = context_system::instance();
        // check user is site admin
        if (has_capability('moodle/site:config', $coursecontext)) {

            // Admin Browse Users
            $this->content->text .= '<a class="tooltip" href="' . $CFG->wwwroot . '/admin/user.php" />
<span class="fa-stack fa-2x">
	<i class="fa fa-group fa-stack-2x fa-fw"></i>
	<i class="fa fa-share fa-stack-1x text-success pull-right-down"></i>
</span>
<span class="text">'. get_string('browseusers', 'block_moodleblock') .'</span>
</a>';

            // Admin Add/Edit Courses 
            $this->content->text .= '<a class="tooltip" href="' . $CFG->wwwroot . '/course/index.php" />
<span class="fa-stack fa-2x">
	<i class="fa fa-th-list  fa-stack-2x fa-fw"></i>
	<i class="fa fa-share fa-stack-1x text-success pull-right-down"></i>
</span>
<span class="text">'. get_string('browsecourses', 'block_moodleblock') .'</span>
</a>';

            // Admin Live Logs  (check if stats are enabled first!)
            if (empty($CFG->enablestats)) {
                // do not show stats icon if stats is disabled
            } else {
                $this->content->text .= '<a class="tooltip" href="' . $CFG->wwwroot . '/report/loglive/index.php?id=1&inpopup=1" target="_blank" />
<span class="fa-stack fa-2x">
	<i class="fa fa-bar-chart-o fa-stack-2x fa-fw"></i>
</span>
<span class="text">'. get_string('livelogs', 'block_moodleblock') .'</span>
</a>';
            }
        }
        
         $this->content->text .= '<form name="form1" method="get" action="' . $CFG->wwwroot . '/course/search.php" id="form1">
    <div class="input-append">
        <input id="navsearchtext" type="text" name="search" placeholder="'. get_string('searchcourses', 'block_moodleblock') .'" >
        <button type="submit" class="btn"><i class="fa fa-search fa-fw" id="navsearchbtn"></i></button>
    </div>
</form>';


/*         $this->content->text .= '<form name="form1" method="get" action="' . $CFG->wwwroot . '/course/search.php" id="form1">
    <div class="input-group">
        <span class="input-group-btn">
		<button type="submit" class="btn btn-default">Go</button>
	</span>
        <input class="form-control" id="navsearchtext" type="text" name="search" placeholder="'. get_string('searchcourses', 'block_moodleblock') .'" >
    </div>
</form>';
*/


        
        $this->content->text .= '</div>';

        // We don't want a footer
        $this->content->footer = "";

        return $this->content;
    }

}
