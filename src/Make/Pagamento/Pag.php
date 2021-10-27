<?php

/**
 * Trait Helper para tags relacionados a pagamento
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

namespace NFePHP\NFe\Make\Pagamento;

trait Pag
{
    /**
     * @var \DOMElement
     */
    protected $pag;

    /**
     * Grupo Pagamento Y pai A01
     * NOTA: Ajustado para NT2016_002_v1.30
     * tag NFe/infNFe/pag (obrigatorio na NT2016_002_v1.30)
     * Obrigatório para 55 e 65
     * @param \stdClass $std
     * @return \DOMElement
     */
    public function tagpag(\stdClass $std)
    {
        $possible = [
            'vTroco'
        ];
        $std = $this->equilizeParameters($std, $possible);
        $pag = $this->dom->createElement("pag");
        //incluso no layout 4.00
        $this->dom->addChild(
            $pag,
            "vTroco",
            $this->conditionalNumberFormatting($std->vTroco),
            false,
            "Valor do troco"
        );
        return $this->pag = $pag;
    }

    /**
     * Insere a tag pag, os detalhamentos dos pagamentos e cartoes
     * NOTA: Ajustado para NT2016_002_v1.30
     * tag NFe/infNFe/pag/
     * tag NFe/infNFe/pag/detPag[]
     * tag NFe/infNFe/pag/detPag[]/Card
     */
    protected function buildTagPag()
    {
        $this->dom->appChild($this->infNFe, $this->pag, 'Falta tag "infNFe"');
    }
}
