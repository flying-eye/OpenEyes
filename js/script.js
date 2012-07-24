/**
 * OpenEyes
 *
 * (C) Moorfields Eye Hospital NHS Foundation Trust, 2008-2011
 * (C) OpenEyes Foundation, 2011-2012
 * This file is part of OpenEyes.
 * OpenEyes is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
 * OpenEyes is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License along with OpenEyes in a file titled COPYING. If not, see <http://www.gnu.org/licenses/>.
 *
 * @package OpenEyes
 * @link http://www.openeyes.org.uk
 * @author OpenEyes <info@openeyes.org.uk>
 * @copyright Copyright (c) 2008-2011, Moorfields Eye Hospital NHS Foundation Trust
 * @copyright Copyright (c) 2011-2012, OpenEyes Foundation
 * @license http://www.gnu.org/licenses/gpl-3.0.html The GNU General Public License V3.0
 */

$('select[id=selected_firm_id]').die('change').live('change', function() {
	var firmId = $('select[id=selected_firm_id]').val();
	$.ajax({
		type: 'post',
		url: '/',
		data: {'selected_firm_id': firmId },
		success: function(data) {
			//console.log(data);
			window.location.href = '/';
		}
	});
});

$(document).ready(function(){
	$('.sprite.showhide').click( function(e){
		e.preventDefault();
		var sprite = $(this).children('span');
		var whiteBox = $(this).parents('.whiteBox');
		
		if(sprite.hasClass('hide')) {
			whiteBox.children('.data_row').slideUp("fast",'fast');
			sprite.removeClass('hide');
			sprite.addClass('show');
		} else {
			whiteBox.children('.data_row').slideDown("fast",'fast');
			sprite.removeClass('show');
			sprite.addClass('hide');
		}
	});

	// show hide
	$('.sprite.showhide2').click(function(e){
		e.preventDefault();
		changeState($(this).parents('.episode_nav'),$(this).children('span'));
	});
	
	function changeState(wb,sp) {
		if (sp.hasClass('hide')) {
			wb.children('.events').slideUp('fast');
			sp.removeClass('hide');
			sp.addClass('show');
		} else {
			wb.children('.events').slideDown('fast');
			sp.removeClass('show');
			sp.addClass('hide');
		}
	}
});

function changeState(wb,sp) {
	if (sp.hasClass('hide')) {
		wb.children('.events').slideUp('fast');
		sp.removeClass('hide');
		sp.addClass('show');
	} else {
		wb.children('.events').slideDown('fast');
		sp.removeClass('show');
		sp.addClass('hide');
	}
}
