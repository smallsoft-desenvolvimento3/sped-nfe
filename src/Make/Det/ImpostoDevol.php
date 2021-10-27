<?php

/**
 * Trait Helper para tags relacionados a Imposto devolvido
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

trait ImpostoDevol
{
    /**
     * @var array<\DOMElement>
     */
    protected $aImpostoDevol = [];

    /**
     * Informação do Imposto devolvido U50 pai H01
     * tag NFe/infNFe/det[]/impostoDevol (opcional)
     * @param \stdClass $std
     * @return \DOMElement
     */
    public function tagimpostoDevol(\stdClass $std)
    {
        $possible = [
            'item',
            'pDevol',
            'vIPIDevol'
        ];
        $std = $this->equilizeParameters($std, $possible);

        // totalizador
        $this->stdTot->vIPIDevol += (float) $std->vIPIDevol;

        $impostoDevol = $this->dom->createElement("impostoDevol");

        $this->dom->addChild(
            $impostoDevol,
            "pDevol",
            $this->conditionalNumberFormatting($std->pDevol, 2),
            true,
            "[item $std->item] Percentual da mercadoria devolvida"
        );
        $parent = $this->dom->createElement("IPI");
        $this->dom->addChild(
            $parent,
            "vIPIDevol",
            $this->conditionalNumberFormatting($std->vIPIDevol),
            true,
            "[item $std->item] Valor do IPI devolvido"
        );
        $impostoDevol->appendChild($parent);
        $this->aImpostoDevol[$std->item] = $impostoDevol;
        return $impostoDevol;
    }
}
