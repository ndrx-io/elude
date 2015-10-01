<?php namespace App\Libraries\Generator\Generators\Migration;

use App\Libraries\Generator\Generators\Code;

/******************************************************************************
 *
 * @package Myo 2
 * @copyright © 2015 by Versusmind.
 * All rights reserved. No part of this document may be
 * reproduced or transmitted in any form or by any means,
 * electronic, mechanical, photocopying, recording, or
 * otherwise, without prior written permission of Versusmind.
 * @link http://www.versusmind.eu/
 *
 * @file Columns.php
 * @author LAHAXE Arnaud
 * @last-edited 17/09/2015
 * @description Columns
 *
 ******************************************************************************/
class ManyToMany extends Code
{
    /**
     * @return string
     */
    public function generate()
    {
        $modelName = $this->get('modelName');

        $lines = [];
        foreach ($this->get('foreignKeys') as $foreignKey) {
            $migration = 'Schema::table(\'%s_%s\', function(Blueprint $table)' . "\n"
                . '{' . "\n"
                . '    $table->foreign(\'%s_id\')->references(\'id\')->on(\'%s\');' . "\n"
                . '    $table->foreign(\'%s_id\')->references(\'id\')->on(\'%s\');' . "\n"
                . '});' . "\n";


            $migration = sprintf($migration, $modelName, $foreignKey, $modelName, $modelName, $foreignKey, $foreignKey);

            $lines[] = $migration;
        }

        return join("\n", $lines);
    }

    /**
     * @return array
     */
    public function options()
    {
        return [
            'foreignKeys',
            'modelName'
        ];
    }
}