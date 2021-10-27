<?php

/**
 * Trait Helper para tags relacionados a NVE
 *
 * @category  API
 * @package   NFePHP\NFe\
 * @copyright Copyright (c) 2008-2021
 * @license   http://www.gnu.org/licenses/lgpl.txt LGPLv3+
 * @license   https://opensource.org/licenses/MIT MIT
 * @license   http://www.gnu.org/licenses/gpl.txt GPLv3+
 * @author    Roberto L. Machado <linux.rlm at gmail dot com>
 * @author    Gustavo Lidani <lidanig0 at gmail dot com>
 * @link      http://github.com/nfephp-org/sped-nfe for the canonical source repository
 */

namespace NFePHP\NFe\Make\Det;

trait NVE
{

    /**
     * @var array<\DOMElement>
     */
    protected $aNVE = [];

    /**
     * NVE NOMENCLATURA DE VALOR ADUANEIRO E ESTATÍSTICA
     * Podem ser até 8 NVE's por item
     *
     * @param \stdClass $std
     * @return \DOMElement|null
     */
    public function tagNVE(\stdClass $std)
    {
        $possible = ['item', 'NVE'];
        $std = $this->equilizeParameters($std, $possible);

        if ($std->NVE == '') {
            return null;
        }
        $nve = $this->dom->createElement("NVE", $std->NVE);
        $this->aNVE[$std->item][] = $nve;
        return $nve;
    }
}
