<?php
namespace NethServer\Module\Hosts\Dhcp;

/*
 * Copyright (C) 2011 Nethesis S.r.l.
 * 
 * This script is part of NethServer.
 * 
 * NethServer is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * NethServer is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with NethServer.  If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * Implement gui module for /etc/hosts configuration
 */
class Modify extends \Nethgui\Controller\Table\Modify
{

    public function initialize()
    {        
        if($this->getIdentifier() === 'delete') {
            $this->setViewTemplate('Nethgui\Template\Table\Delete');
        } else {
            $this->setViewTemplate('NethServer\Template\Hosts\Dhcp\Modify');
        }        
    }

    public function validate(\Nethgui\Controller\ValidationReportInterface $report)
    {
        // Bind the dhcp-reservation platform validator:
        $this->getValidator('IpAddress')
            ->platform('dhcp-reservation', $this->parameters['MacAddress']);

        parent::validate($report);
    }


    public function onParametersSaved($changes)
    {
        $actionName = $this->getIdentifier();
        if ($actionName === 'update') {
            $actionName = 'modify';
        }
        $this->getPlatform()->signalEvent(sprintf('host-%s@post-process', $actionName), array($this->parameters['hostname']));
    }

}