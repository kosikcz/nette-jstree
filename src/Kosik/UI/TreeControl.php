<?php

namespace Kosik\UI;

use Nette\InvalidArgumentException;
use Nette\InvalidStateException;
use Nette\Utils\Html;

/**
 * Dynamic virtual-mode tree control.
 *
 * @see https://github.com/vakata/jstree
 *
 * @author Tomáš Čepička <tomas.cepicka@kosik.cz>
 * @package Kosik\UI
 */
class TreeControl extends HtmlControl
{
    const BASE_CLASS = 'nette-jstree';

    const ROOT_CHARACTER = '#';


    /** @var callable */
    private $nodeLoadCallback;

    /** @var callable|array */
    public $onNodeClick;


    protected function createHtml()
    {
        if(!$this->hasNodeLoadCallback()) {
            throw new InvalidStateException("Load callback is not set.");
        }
        return Html::el('div')
            ->class(self::BASE_CLASS)
            ->data('load-url', $this->link('load!'))
            ->data('execute-url', $this->link('execute!'));
    }

    public function handleLoad()
    {
        $identifier = $this->getSignalIdentifier();

        if(!$this->hasNodeLoadCallback()) {
            throw new InvalidStateException("Load callback is not set.");
        }

        $callback = $this->getNodeLoadCallback();

        $data = $callback($identifier);

        if(!is_array($data)) {
            throw new InvalidArgumentException("Load callback must return an array of TreeNode instances.");
        }

        $this->getPresenter()->sendJson(array_map(function(TreeNode $node) use ($identifier) {
            return $this->createNodeData($node, $identifier);
        }, $data));
    }

    public function handleExecute()
    {
        $identifier = $this->getSignalIdentifier();
        $this->onNodeClick($identifier);
    }

    protected function getSignalIdentifier()
    {
        $identifier = $this->getPresenter()->getRequest()->getParameter('id');
        return $identifier != self::ROOT_CHARACTER ? $identifier : NULL;
    }

    /**
     * Creates an array of a given node data.
     *
     * @param TreeNode $node
     * @param string $parent
     * @return array
     */
    private function createNodeData(TreeNode $node, $parent)
    {
        return array(
            'id' => $node->getIdentifier(),
            'parent' => $parent ?? self::ROOT_CHARACTER,
            'text' => $node->getText(),
            'icon' => 'fa fa-' . $node->getIcon()->getName(),
            'children' => !$node->getLast()
        );
    }


    /**
     * Checks if the control has the node load callback set.
     *
     * @return bool
     */
    public function hasNodeLoadCallback() : bool
    {
        return is_callable($this->nodeLoadCallback);
    }

    /**
     * Sets the node load callback.
     *
     * @param $callback
     */
    public function setNodeLoadCallback($callback)
    {
        $this->nodeLoadCallback = $callback;
    }

    /**
     * Returns the node load callback, or NULL if not set.
     *
     * @return callable|null
     */
    public function getNodeLoadCallback()
    {
        return $this->nodeLoadCallback;
    }
}