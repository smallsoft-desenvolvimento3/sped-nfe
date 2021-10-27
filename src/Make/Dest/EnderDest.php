<?php

/**
 * Trait Helper para tags relacionados a identificação do endereço do destinatário
 *
 * Essa trait depende da \NFePHP\NFe\Make\Dest\Dest
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

namespace NFePHP\NFe\Make\Dest;

trait EnderDest
{
    /**
     * @var \DOMElement
     */
    protected $enderDest;

    /**
     * Endereço do Destinatário da NF-e E05 pai E01
     * tag NFe/infNFe/dest/enderDest  (opcional para modelo 65)
     * Os dados do destinatário devem ser inseridos antes deste método
     * @param \stdClass $std
     * @return \DOMElement
     */
    public function tagenderDest(\stdClass $std)
    {
        $possible = [
            'xLgr',
            'nro',
            'xCpl',
            'xBairro',
            'cMun',
            'xMun',
            'UF',
            'CEP',
            'cPais',
            'xPais',
            'fone'
        ];
        $std = $this->equilizeParameters($std, $possible);

        $identificador = 'E05 <enderDest> - ';

        if (empty($this->dest)) {
            throw new \RuntimeException('A TAG dest deve ser criada antes do endereço do mesmo.');
        }

        $this->enderDest = $this->dom->createElement("enderDest");

        $this->dom->addChild(
            $this->enderDest,
            "xLgr",
            $std->xLgr,
            true,
            $identificador . "Logradouro do Endereço do Destinatário"
        );
        $this->dom->addChild(
            $this->enderDest,
            "nro",
            $std->nro,
            true,
            $identificador . "Número do Endereço do Destinatário"
        );
        $this->dom->addChild(
            $this->enderDest,
            "xCpl",
            $std->xCpl,
            false,
            $identificador . "Complemento do Endereço do Destinatário"
        );
        $this->dom->addChild(
            $this->enderDest,
            "xBairro",
            $std->xBairro,
            true,
            $identificador . "Bairro do Endereço do Destinatário"
        );
        $this->dom->addChild(
            $this->enderDest,
            "cMun",
            $std->cMun,
            true,
            $identificador . "Código do município do Endereço do Destinatário"
        );
        $this->dom->addChild(
            $this->enderDest,
            "xMun",
            $std->xMun,
            true,
            $identificador . "Nome do município do Endereço do Destinatário"
        );
        $this->dom->addChild(
            $this->enderDest,
            "UF",
            $std->UF,
            true,
            $identificador . "Sigla da UF do Endereço do Destinatário"
        );
        $this->dom->addChild(
            $this->enderDest,
            "CEP",
            $std->CEP,
            false,
            $identificador . "Código do CEP do Endereço do Destinatário"
        );
        $this->dom->addChild(
            $this->enderDest,
            "cPais",
            $std->cPais,
            false,
            $identificador . "Código do País do Endereço do Destinatário"
        );
        $this->dom->addChild(
            $this->enderDest,
            "xPais",
            $std->xPais,
            false,
            $identificador . "Nome do País do Endereço do Destinatário"
        );
        $this->dom->addChild(
            $this->enderDest,
            "fone",
            $std->fone,
            false,
            $identificador . "Telefone do Endereço do Destinatário"
        );
        $node = $this->dest->getElementsByTagName("indIEDest")->item(0);
        if (!isset($node)) {
            $node = $this->dest->getElementsByTagName("IE")->item(0);
        }
        $this->dest->insertBefore($this->enderDest, $node);
        return $this->enderDest;
    }
}
