<?php

/**
 * Trait Helper para tags relacionados ao Intermediador
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

namespace NFePHP\NFe\Make\InfNFe;

trait Intermed
{
    /**
     * @var \DOMElement
     */
    protected $intermed;

    /**
     * Dados do intermediador
     * @param \stdClass $std
     * @return \DomElement
     */
    public function tagIntermed(\stdClass $std)
    {
        $possible = [
            'CNPJ',
            'idCadIntTran'
        ];
        $std = $this->equilizeParameters($std, $possible);
        $tag = $this->dom->createElement("infIntermed");
        $this->dom->addChild(
            $tag,
            "CNPJ",
            $std->CNPJ,
            true,
            "CNPJ do Intermediador da Transação (agenciador, plataforma de "
                . "delivery, marketplace e similar) de serviços e de negócios"
        );
        $this->dom->addChild(
            $tag,
            "idCadIntTran",
            $std->idCadIntTran,
            true,
            "Identificador cadastrado no intermediador"
        );
        return $this->intermed = $tag;
    }
}
