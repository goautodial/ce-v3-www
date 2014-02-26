<?php 
/***************************************************************************
 *   Copyright (C) 2006 by phpSysInfo - A PHP System Information Script    *
 *   http://phpsysinfo.sourceforge.net/                                    *
 *                                                                         *
 *   This program is free software; you can redistribute it and/or modify  *
 *   it under the terms of the GNU General Public License as published by  *
 *   the Free Software Foundation; either version 2 of the License, or     *
 *   (at your option) any later version.                                   *
 *                                                                         *
 *   This program is distributed in the hope that it will be useful,       *
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of        *
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the         *
 *   GNU General Public License for more details.                          *
 *                                                                         *
 *   You should have received a copy of the GNU General Public License     *
 *   along with this program; if not, write to the                         *
 *   Free Software Foundation, Inc.,                                       *
 *   59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.             *
 ***************************************************************************/

// $Id: vitals.php,v 1.32 2007/02/18 18:59:54 bigmichi1 Exp $

// xml_vitals()

function xml_vitals () {
	global $sysinfo;
	global $loadbar;
	global $show_vhostname;
	
	$strLoadavg = "";
	$arrBuf = ( $loadbar ? $sysinfo->loadavg( $loadbar ) : $sysinfo->loadavg() );
	
	foreach( $arrBuf['avg'] as $strValue) {
		$strLoadavg .= $strValue . ' ';
	}
	
	$_text = "  <Vitals>\n"
		. "    <Hostname>" . htmlspecialchars( $show_chostname ? $sysinfo->chostname() : $sysinfo->chostname(), ENT_QUOTES ) . "</Hostname>\n"
		. "    <IPAddr>" . htmlspecialchars( $show_ip_addr ? $sysinfo->ip_addr() : $sysinfo->ip_addr(), ENT_QUOTES ) . "</IPAddr>\n"
		. "    <Kernel>" . htmlspecialchars( $sysinfo->kernel(), ENT_QUOTES ) . "</Kernel>\n"
		. "    <Distro>" . htmlspecialchars( $sysinfo->distro(), ENT_QUOTES ) . "</Distro>\n"
		. "    <Distroicon>" . htmlspecialchars( $sysinfo->distroicon(), ENT_QUOTES ) . "</Distroicon>\n"
		. "    <Uptime>" . htmlspecialchars( $sysinfo->uptime(), ENT_QUOTES ) . "</Uptime>\n"
		. "    <LoadAvg>" . htmlspecialchars( trim( $strLoadavg ), ENT_QUOTES ) . "</LoadAvg>\n";
	if( isset( $arrBuf['cpupercent'] ) ) {
		$_text .= "   <CPULoad>" . htmlspecialchars( round( $arrBuf['cpupercent'], 2 ), ENT_QUOTES ) . "</CPULoad>";
	}
	$_text .= "  </Vitals>\n";
	
	return $_text;
} 

// html_vitals()
function html_vitals () {
	global $sysinfo;
	global $loadbar;
	global $show_vhostname;
	global $webpath;
	global $XPath;
	global $text;
	
	$textdir = direction();
	$scale_factor = 1;
	$strLoadbar = "";
	$uptime = "";
	
	if( $XPath->match( "/phpsysinfo/Vitals/CPULoad" ) )
		$strLoadbar = "<br>" . create_bargraph( $XPath->getData( "/phpsysinfo/Vitals/CPULoad" ), 100, $scale_factor ) . "&nbsp;" . $XPath->getData( "/phpsysinfo/Vitals/CPULoad" ) . "%";
	
	$_text = "<table border=\"0\" width=\"100%\" align=\"center\">\n"
		. "  <tr>\n"
		. "    <td class=\"tabdata\" valign=\"top\">Hostname</font></td>\n"
		. "    <td class=\"tabdata\">" . $sysinfo->phostname() . "</font></td>\n"
		. "    <td class=\"tabdata\" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</font></td>\n"
		. "    <td class=\"tabdata\" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</font></td>\n"
		. "  </tr>\n"
		. "  <tr>\n"
		. "    <td class=\"tabdata\" valign=\"top\">" . $text['ip'] . "</font></td>\n"
		. "    <td class=\"tabdata\">" . $sysinfo->vip_addr() . "</font></td>\n"
		. "    <td class=\"tabdata\" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</font></td>\n"
		. "    <td class=\"tabdata\" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</font></td>\n"
		. "  </tr>\n"	
	
		. "  <tr>\n"
		. "    <td class=\"tabdata\" valign=\"top\">" . $text['kversion'] . "</font></td>\n"
		. "    <td class=\"tabdata\" >" . $XPath->getData( "/phpsysinfo/Vitals/Kernel" ) . "</font></td>\n"
		. "    <td class=\"tabdata\" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</font></td>\n"
		. "    <td class=\"tabdata\" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</font></td>\n"
		. "  </tr>\n"
		. "  <tr>\n"
		. "    <td class=\"tabdata\" valign=\"top\">" . $text['dversion'] . "</font></td>\n"
		. "    <td class=\"tabdata\"><img width=\"10\" height=\"10\" alt=\"\" src=\"" . $webpath . "images/" . $XPath->getData( "/phpsysinfo/Vitals/Distroicon" ) . "\">&nbsp;" . $XPath->getData("/phpsysinfo/Vitals/Distro") . "</font></td>\n"
		. "    <td class=\"tabdata\" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</font></td>\n"
		. "    <td class=\"tabdata\" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</font></td>\n"
		. "  </tr>\n"
		. "  <tr>\n"
		. "    <td class=\"tabdata\" valign=\"top\">" . $text['uptime'] . "</font></td>\n"
		. "    <td class=\"tabdata\">" . uptime( $XPath->getData( "/phpsysinfo/Vitals/Uptime" ) ) . "</font></td>\n"
		. "    <td class=\"tabdata\" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</font></td>\n"
		. "    <td class=\"tabdata\" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</font></td>\n"
		. "  </tr>\n"
		. "  <tr>\n"
		. "    <td class=\"tabdata\" valign=\"top\">" . $text['loadavg'] . "</font></td>\n"
		. "    <td class=\"tabdata\">" . $XPath->getData( "/phpsysinfo/Vitals/LoadAvg" ) . $strLoadbar . "</font></td>\n"
		. "    <td class=\"tabdata\" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</font></td>\n"
		. "    <td class=\"tabdata\" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</font></td>\n"
		. "  </tr>\n"
		. "</table>\n";
	
	return $_text;
} 

function wml_vitals () {
	global $XPath;
	global $text;
	
	$_text = "<card id=\"vitals\" title=\"" . $text['vitals']  . "\">\n"
		. "<p>" . $text['hostname'] . ":<br/>\n"
		. "-&nbsp;" . $XPath->getData( "/phpsysinfo/Vitals/Hostname" ) . "</p>\n"
		. "<p>" . $text['ip'] . ":<br/>\n"
		. "-&nbsp;" . $XPath->getData( "/phpsysinfo/Vitals/IPAddr" ) . "</p>\n"	
		. "<p>" . $text['kversion'] . ":<br/>\n"
		. "-&nbsp;" . $XPath->getData( "/phpsysinfo/Vitals/Kernel" ) . "</p>\n"
		. "<p>" . $text['uptime'] . ":<br/>\n"
		. "-&nbsp;" . uptime( $XPath->getData( "/phpsysinfo/Vitals/Uptime" ) ) . "</p>\n"
		. "<p>" . $text['loadavg'] . ":<br/>"
		. "-&nbsp;" . $XPath->getData( "/phpsysinfo/Vitals/LoadAvg" ) . "</p>\n"
		. "</card>\n";
	
	return $_text;
}
?>