<?php

/**
 * Trait Helper para tags relacionados à balsa de transporte
 *
 * Essa trait depende da \NFePHP\NFe\Make\Transporte\Transp
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

namespace NFePHP\NFe\Make\Transporte;

trait Vol
{
    /**
     * @var array<\DOMElement>
     */
    protected $aVol = [];

    /**
     * Grupo Volumes X26 pai X01
     * tag NFe/infNFe/transp/vol (opcional)
     * @param \stdClass $std
     * @return \DOMElement
     */
    public function tagvol(\stdClass $std)
    {
        $possible = [
            'item',
            'qVol',
            'esp',
            'marca',
            'nVol',
            'pesoL',
            'pesoB'
        ];
        $std = $this->equilizeParameters($std, $possible);
        $vol = $this->dom->createElement("vol");
        $this->dom->addChild(
            $vol,
            "qVol",
            $this->conditionalNumberFormatting($std->qVol, 0),
            false,
            "Quantidade de volumes transportados"
        );
        $this->dom->addChild(
            $vol,
            "esp",
            $std->esp,
            false,
            "Espécie dos volumes transportados"
        );
        $this->dom->addChild(
            $vol,
            "marca",
            $std->marca,
            false,
            "Marca dos volumes transportados"
        );
        $this->dom->addChild(
            $vol,
            "nVol",
            $std->nVol,
            false,
            "Numeração dos volumes transportados"
        );
        $this->dom->addChild(
            $vol,
            "pesoL",
            $this->conditionalNumberFormatting($std->pesoL, 3),
            false,
            "Peso Líquido (em kg) dos volumes transportados"
        );
        $this->dom->addChild(
            $vol,
            "pesoB",
            $this->conditionalNumberFormatting($std->pesoB, 3),
            false,
            "Peso Bruto (em kg) dos volumes transportados"
        );
        $this->aVol[$std->item] = $vol;
        return $vol;
    }

    /**
     * Node vol
     */
    protected function buildVol()
    {
        foreach ($this->aVol as $num => $vol) {
            $this->dom->appChild($this->transp, $vol, "Inclusão do node vol");
        }
    }
}
