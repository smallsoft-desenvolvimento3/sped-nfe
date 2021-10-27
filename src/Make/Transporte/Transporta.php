<?php

/**
 * Trait Helper para tags relacionados à identificação da transportadora
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

trait Transporta
{

    /**
     * Grupo Transportador X03 pai X01
     * tag NFe/infNFe/transp/tranporta (opcional)
     * @param \stdClass $std
     * @return \DOMElement
     */
    public function tagtransporta(\stdClass $std)
    {
        $possible = [
            'xNome',
            'IE',
            'xEnder',
            'xMun',
            'UF',
            'CNPJ',
            'CPF'
        ];
        $std = $this->equilizeParameters($std, $possible);

        $transporta = $this->dom->createElement('transporta');

        $this->dom->addChild(
            $transporta,
            "CNPJ",
            $std->CNPJ,
            false,
            "CNPJ do Transportador"
        );
        $this->dom->addChild(
            $transporta,
            "CPF",
            $std->CPF,
            false,
            "CPF do Transportador"
        );
        $this->dom->addChild(
            $transporta,
            "xNome",
            $std->xNome,
            false,
            "Razão Social ou nome do Transportador"
        );
        $this->dom->addChild(
            $transporta,
            "IE",
            $std->IE,
            false,
            "Inscrição Estadual do Transportador"
        );
        $this->dom->addChild(
            $transporta,
            "xEnder",
            $std->xEnder,
            false,
            "Endereço Completo do Transportador"
        );
        $this->dom->addChild(
            $transporta,
            "xMun",
            $std->xMun,
            false,
            "Nome do município do Transportador"
        );
        $this->dom->addChild(
            $transporta,
            "UF",
            $std->UF,
            false,
            "Sigla da UF do Transportador"
        );
        $this->dom->appChild(
            $this->transp,
            $transporta,
            'A tag transp deveria ter sido carregada primeiro.'
        );
        $this->dom->appChild($this->transp, $transporta, "Inclusão do node vol");
        return $transporta;
    }
}
