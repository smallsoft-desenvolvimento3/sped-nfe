<?php

/**
 * Trait Helper para tags relacionados a Informações adicionais
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

namespace NFePHP\NFe\Make\InfAdic;

trait InfAdic
{
    /**
     * @var \DOMElement
     */
    protected $infAdic;

    /**
     * Grupo de Informações Adicionais Z01 pai A01
     * tag NFe/infNFe/infAdic (opcional)
     * @param \stdClass $std
     * @return \DOMElement
     */
    public function taginfAdic(\stdClass $std)
    {
        $possible = ['infAdFisco', 'infCpl'];
        $std = $this->equilizeParameters($std, $possible);
        $this->buildInfAdic();
        $this->dom->addChild(
            $this->infAdic,
            "infAdFisco",
            $std->infAdFisco,
            false,
            "Informações Adicionais de Interesse do Fisco"
        );
        $this->dom->addChild(
            $this->infAdic,
            "infCpl",
            $std->infCpl,
            false,
            "Informações Complementares de interesse do Contribuinte"
        );
        return $this->infAdic;
    }

    /**
     * Grupo de Informações Adicionais Z01 pai A01
     * tag NFe/infNFe/infAdic (opcional)
     * Função chamada pelos metodos
     * [taginfAdic] [tagobsCont] [tagobsFisco] [tagprocRef]
     * @return DOMElement
     */
    protected function buildInfAdic()
    {
        if (empty($this->infAdic)) {
            $this->infAdic = $this->dom->createElement("infAdic");
        }
        return $this->infAdic;
    }
}
