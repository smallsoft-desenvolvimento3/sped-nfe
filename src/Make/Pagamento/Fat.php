<?php

/**
 * Trait Helper para tags relacionados a fatura
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

trait Fat
{
    /**
     * @var \DOMElement
     */
    protected $cobr;

    /**
     * Grupo Fatura Y02 pai Y01
     * tag NFe/infNFe/cobr/fat (opcional)
     * @param \stdClass $std
     * @return \DOMElement
     */
    public function tagfat(\stdClass $std)
    {
        $possible = [
            'nFat',
            'vOrig',
            'vDesc',
            'vLiq'
        ];
        $std = $this->equilizeParameters($std, $possible);
        $this->buildCobr();
        $fat = $this->dom->createElement("fat");
        $this->dom->addChild(
            $fat,
            "nFat",
            $std->nFat,
            false,
            "Número da Fatura"
        );
        $this->dom->addChild(
            $fat,
            "vOrig",
            $this->conditionalNumberFormatting($std->vOrig),
            false,
            "Valor Original da Fatura"
        );
        $this->dom->addChild(
            $fat,
            "vDesc",
            $this->conditionalNumberFormatting($std->vDesc),
            false,
            "Valor do desconto"
        );
        $this->dom->addChild(
            $fat,
            "vLiq",
            $this->conditionalNumberFormatting($std->vLiq),
            false,
            "Valor Líquido da Fatura"
        );
        $this->dom->appChild($this->cobr, $fat);
        return $fat;
    }

    /**
     * Grupo Duplicata Y07 pai Y02
     * tag NFe/infNFe/cobr/fat/dup (opcional)
     * É necessário criar a tag fat antes de criar as duplicatas
     * @param \stdClass $std
     * @return \DOMElement
     */
    public function tagdup(\stdClass $std)
    {
        $possible = [
            'nDup',
            'dVenc',
            'vDup'
        ];
        $std = $this->equilizeParameters($std, $possible);
        $this->buildCobr();
        $dup = $this->dom->createElement("dup");
        $this->dom->addChild(
            $dup,
            "nDup",
            $std->nDup,
            false,
            "Número da Duplicata"
        );
        $this->dom->addChild(
            $dup,
            "dVenc",
            $std->dVenc,
            false,
            "Data de vencimento"
        );
        $this->dom->addChild(
            $dup,
            "vDup",
            $this->conditionalNumberFormatting($std->vDup),
            true,
            "Valor da duplicata"
        );
        $this->dom->appChild($this->cobr, $dup, 'Inclui duplicata na tag cobr');
        return $dup;
    }

    /**
     * Grupo Cobrança Y01 pai A01
     * tag NFe/infNFe/cobr (opcional)
     * Depende de fat
     */
    protected function buildCobr()
    {
        if (empty($this->cobr)) {
            $this->cobr = $this->dom->createElement("cobr");
        }
    }
}
