<?php

namespace Kosik\UI;

use Kosik\Utils\Icon;
use Nette\Object;

/**
 * Represents a tree node.
 *
 * @author Tomáš Čepička <tomas.cepicka@kosik.cz>
 * @package Kosik\UI
 */
class TreeNode extends Object
{
    /** @var string */
    private $identifier;

    /** @var string */
    private $text;

    /** @var Icon|null */
    private $icon;

    /** @var boolean */
    private $last;


    /**
     * Creates a new instance of TreeNode.
     *
     * @param string $identifier
     * @param string $text
     * @param Icon|null $icon
     * @param bool $last
     */
    public function __construct(string $identifier, string $text, Icon $icon = NULL, $last = TRUE)
    {
        $this->identifier = $identifier;
        $this->text = $text;
        $this->icon = $icon;
        $this->last = $last;
    }

    /**
     * Returns the identifier string of the node.
     *
     * @return string
     */
    public function getIdentifier() : string
    {
        return $this->identifier;
    }

    /**
     * Returns the label text of the node.
     *
     * @return string
     */
    public function getText() : string
    {
        return $this->text;
    }

    /**
     * Returns the icon of the node, or NULL if not set.
     *
     * @return Icon|null
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * Returns a value indicating if the node is a last node in the hierarchy (has no children).
     *
     * @return bool
     */
    public function getLast() : bool
    {
        return $this->last;
    }
}