<?php

/**
 * Trait Helper para tags relacionados a Imposto de Importação
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

trait II
{
    /**
     * @var array<\DOMElement>
     */
    protected $aII = [];

    /**
     * Grupo Imposto de Importação P01 pai M01
     * tag NFe/infNFe/det[]/imposto/II
     * @param \stdClass $std
     * @return \DOMElement
     */
    public function tagII(\stdClass $std)
    {
        $possible = [
            'item',
            'vBC',
            'vDespAdu',
            'vII',
            'vIOF'
        ];
        $std = $this->equilizeParameters($std, $possible);

        // totalizador
        $this->stdTot->vII += (float) $std->vII;

        $tii = $this->dom->createElement('II');

        $this->dom->addChild(
            $tii,
            "vBC",
            $this->conditionalNumberFormatting($std->vBC),
            true,
            "[item $std->item] Valor BC do Imposto de Importação"
        );
        $this->dom->addChild(
            $tii,
            "vDespAdu",
            $this->conditionalNumberFormatting($std->vDespAdu),
            true,
            "[item $std->item] Valor despesas aduaneiras"
        );
        $this->dom->addChild(
            $tii,
            "vII",
            $this->conditionalNumberFormatting($std->vII),
            true,
            "[item $std->item] Valor Imposto de Importação"
        );
        $this->dom->addChild(
            $tii,
            "vIOF",
            $this->conditionalNumberFormatting($std->vIOF),
            true,
            "[item $std->item] Valor Imposto sobre Operações Financeiras"
        );
        $this->aII[$std->item] = $tii;
        return $tii;
    }
}
