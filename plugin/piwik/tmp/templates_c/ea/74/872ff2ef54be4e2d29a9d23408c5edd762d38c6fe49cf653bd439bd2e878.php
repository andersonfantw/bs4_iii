<?php

/* @CoreHome/_topBarTopMenu.twig */
class __TwigTemplate_ea74872ff2ef54be4e2d29a9d23408c5edd762d38c6fe49cf653bd439bd2e878 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "<div id=\"topRightBar\">

    ";
        // line 14
        echo "
    ";
        // line 24
        echo "
    ";
        // line 25
        if ( !array_key_exists("topMenuModule", $context)) {
            // line 26
            echo "        ";
            $context["topMenuModule"] = $this->getContext($context, "currentModule");
            // line 27
            echo "        ";
            $context["topMenuAction"] = $this->getContext($context, "currentAction");
            // line 28
            echo "    ";
        }
        // line 29
        echo "
    ";
        // line 30
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable($this->getContext($context, "topMenu"));
        $context['loop'] = array(
          'parent' => $context['_parent'],
          'index0' => 0,
          'index'  => 1,
          'first'  => true,
        );
        if (is_array($context['_seq']) || (is_object($context['_seq']) && $context['_seq'] instanceof Countable)) {
            $length = count($context['_seq']);
            $context['loop']['revindex0'] = $length - 1;
            $context['loop']['revindex'] = $length;
            $context['loop']['length'] = $length;
            $context['loop']['last'] = 1 === $length;
        }
        foreach ($context['_seq'] as $context["label"] => $context["menu"]) {
            // line 31
            echo "        ";
            if ( !$this->getAttribute($context["loop"], "first", array())) {
                // line 32
                echo "                |
        ";
            }
            // line 34
            echo "        ";
            echo $this->getAttribute($this, "topMenuItem", array(0 => $context["label"], 1 => $context["menu"], 2 => $this->getContext($context, "topMenuModule"), 3 => $this->getContext($context, "topMenuAction")), "method");
            echo "
    ";
            ++$context['loop']['index0'];
            ++$context['loop']['index'];
            $context['loop']['first'] = false;
            if (isset($context['loop']['length'])) {
                --$context['loop']['revindex0'];
                --$context['loop']['revindex'];
                $context['loop']['last'] = 0 === $context['loop']['revindex0'];
            }
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['label'], $context['menu'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 36
        echo "
</div>
";
    }

    // line 3
    public function gettopMenuItem($__label__ = null, $__menu__ = null, $__currentModule__ = null, $__currentAction__ = null)
    {
        $context = $this->env->mergeGlobals(array(
            "label" => $__label__,
            "menu" => $__menu__,
            "currentModule" => $__currentModule__,
            "currentAction" => $__currentAction__,
        ));

        $blocks = array();

        ob_start();
        try {
            // line 4
            echo "        ";
            if ($this->getAttribute($this->getContext($context, "menu", true), "_html", array(), "any", true, true)) {
                // line 5
                echo "            ";
                echo $this->getAttribute($this->getContext($context, "menu"), "_html", array());
                echo "
        ";
            } elseif ((($this->getAttribute($this->getAttribute(            // line 6
$this->getContext($context, "menu"), "_url", array()), "module", array()) == $this->getContext($context, "currentModule")) && (twig_test_empty($this->getAttribute($this->getAttribute($this->getContext($context, "menu"), "_url", array()), "action", array())) || ($this->getAttribute($this->getAttribute($this->getContext($context, "menu"), "_url", array()), "action", array()) == $this->getContext($context, "currentAction"))))) {
                // line 7
                echo "            <span class=\"topBarElem topBarElemActive\"><strong>";
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array($this->getContext($context, "label"))), "html", null, true);
                echo "</strong></span>
        ";
            } else {
                // line 9
                echo "            <span class=\"topBarElem\" ";
                if ($this->getAttribute($this->getContext($context, "menu", true), "_tooltip", array(), "any", true, true)) {
                    echo "title=\"";
                    echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, "menu"), "_tooltip", array()), "html", null, true);
                    echo "\"";
                }
                echo ">
            <a id=\"topmenu-";
                // line 10
                echo twig_escape_filter($this->env, twig_lower_filter($this->env, $this->getAttribute($this->getAttribute($this->getContext($context, "menu"), "_url", array()), "module", array())), "html", null, true);
                echo "\" href=\"index.php";
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('urlRewriteWithParameters')->getCallable(), array($this->getAttribute($this->getContext($context, "menu"), "_url", array()))), "html", null, true);
                echo "\">";
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array($this->getContext($context, "label"))), "html", null, true);
                echo "</a>
        </span>
        ";
            }
            // line 13
            echo "    ";
        } catch (Exception $e) {
            ob_end_clean();

            throw $e;
        }

        return ('' === $tmp = ob_get_clean()) ? '' : new Twig_Markup($tmp, $this->env->getCharset());
    }

    // line 15
    public function getuserMenuItem($__label__ = null, $__menu__ = null, $__currentModule__ = null, $__currentAction__ = null)
    {
        $context = $this->env->mergeGlobals(array(
            "label" => $__label__,
            "menu" => $__menu__,
            "currentModule" => $__currentModule__,
            "currentAction" => $__currentAction__,
        ));

        $blocks = array();

        ob_start();
        try {
            // line 16
            echo "
        <a class=\"item ";
            // line 17
            if ((($this->getAttribute($this->getAttribute($this->getContext($context, "menu"), "_url", array()), "module", array()) == $this->getContext($context, "currentModule")) && (twig_test_empty($this->getAttribute($this->getAttribute($this->getContext($context, "menu"), "_url", array()), "action", array())) || ($this->getAttribute($this->getAttribute($this->getContext($context, "menu"), "_url", array()), "action", array()) == $this->getContext($context, "currentAction"))))) {
                echo "active";
            }
            echo "\"
           href=\"index.php";
            // line 18
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('urlRewriteWithParameters')->getCallable(), array($this->getAttribute($this->getContext($context, "menu"), "_url", array()))), "html", null, true);
            echo "\"
           id=\"usermenu-";
            // line 19
            echo twig_escape_filter($this->env, twig_lower_filter($this->env, $this->getAttribute($this->getAttribute($this->getContext($context, "menu"), "_url", array()), "module", array())), "html", null, true);
            echo "-";
            echo twig_escape_filter($this->env, twig_lower_filter($this->env, (($this->getAttribute($this->getAttribute($this->getContext($context, "menu", true), "_url", array(), "any", false, true), "action", array(), "any", true, true)) ? (_twig_default_filter($this->getAttribute($this->getAttribute($this->getContext($context, "menu", true), "_url", array(), "any", false, true), "action", array()), "index")) : ("index"))), "html", null, true);
            echo "\"
           ";
            // line 20
            if ($this->getAttribute($this->getContext($context, "menu", true), "_tooltip", array(), "any", true, true)) {
                echo "title=\"";
                echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, "menu"), "_tooltip", array()), "html", null, true);
                echo "\"";
            }
            // line 21
            echo "                >";
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array($this->getContext($context, "label"))), "html", null, true);
            echo "</a>

    ";
        } catch (Exception $e) {
            ob_end_clean();

            throw $e;
        }

        return ('' === $tmp = ob_get_clean()) ? '' : new Twig_Markup($tmp, $this->env->getCharset());
    }

    public function getTemplateName()
    {
        return "@CoreHome/_topBarTopMenu.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  189 => 21,  183 => 20,  177 => 19,  173 => 18,  167 => 17,  164 => 16,  150 => 15,  139 => 13,  129 => 10,  120 => 9,  114 => 7,  112 => 6,  107 => 5,  104 => 4,  90 => 3,  84 => 36,  67 => 34,  63 => 32,  60 => 31,  43 => 30,  40 => 29,  37 => 28,  34 => 27,  31 => 26,  29 => 25,  26 => 24,  23 => 14,  19 => 1,);
    }
}
