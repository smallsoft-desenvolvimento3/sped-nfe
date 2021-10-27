<?php

/**
 * Trait Helper para tags relacionados ao veículo de transporte
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

trait VeicTransp
{

    /**
     * Grupo Veículo Transporte X18 pai X17.1
     * tag NFe/infNFe/transp/veicTransp (opcional)
     * Ajustado NT 2020.005 v1.20
     * @param \stdClass $std
     * @return \DOMElement
     */
    public function tagveicTransp(\stdClass $std)
    {
        $possible = [
            'placa',
            'UF',
            'RNTC'
        ];
        $std = $this->equilizeParameters($std, $possible);
        $veicTransp = $this->dom->createElement("veicTransp");
        $this->dom->addChild(
            $veicTransp,
            "placa",
            $std->placa,
            true,
            "Placa do Veículo"
        );
        $this->dom->addChild(
            $veicTransp,
            "UF",
            $std->UF,
            false,
            "Sigla da UF do Veículo"
        );
        $this->dom->addChild(
            $veicTransp,
            "RNTC",
            $std->RNTC,
            false,
            "Registro Nacional de Transportador de Carga (ANTT) do Veículo"
        );
        $this->dom->appChild(
            $this->transp,
            $veicTransp,
            'A tag transp deveria ter sido carregada primeiro.'
        );
        return $veicTransp;
    }
}
