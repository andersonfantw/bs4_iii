<?php

/* @CoreHome/macros.twig */
class __TwigTemplate_b29f1c4f2fb61a78d5e6cf14edb8cd9f31b8ea2aca3ba1701da9cb7533afa597 extends Twig_Template
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
    }

    // line 1
    public function getsidebarMenu($__sidebarMenu__ = null, $__currentModule__ = null, $__currentAction__ = null)
    {
        $context = $this->env->mergeGlobals(array(
            "sidebarMenu" => $__sidebarMenu__,
            "currentModule" => $__currentModule__,
            "currentAction" => $__currentAction__,
        ));

        $blocks = array();

        ob_start();
        try {
            // line 2
            echo "    ";
            if ((twig_length_filter($this->env, $this->getContext($context, "sidebarMenu")) > 1)) {
                // line 3
                echo "        <div class=\"Menu Menu--admin\">
            <ul class=\"Menu-tabList\">
                ";
                // line 5
                $context['_parent'] = (array) $context;
                $context['_seq'] = twig_ensure_traversable($this->getContext($context, "sidebarMenu"));
                foreach ($context['_seq'] as $context["name"] => $context["submenu"]) {
                    // line 6
                    echo "                    ";
                    if ($this->getAttribute($context["submenu"], "_hasSubmenu", array())) {
                        // line 7
                        echo "                        <li>
                            <span>";
                        // line 8
                        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array($context["name"])), "html", null, true);
                        echo "</span>
                            <ul>
                                ";
                        // line 10
                        $context['_parent'] = (array) $context;
                        $context['_seq'] = twig_ensure_traversable($context["submenu"]);
                        foreach ($context['_seq'] as $context["sname"] => $context["url"]) {
                            // line 11
                            echo "                                    ";
                            if ((twig_slice($this->env, $context["sname"], 0, 1) != "_")) {
                                // line 12
                                echo "                                        <li>
                                            <a href='index.php";
                                // line 13
                                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('urlRewriteWithParameters')->getCallable(), array($this->getAttribute($context["url"], "_url", array()))), "html", null, true);
                                echo "'
                                               target=\"_self\"
                                               ";
                                // line 15
                                if ((($this->getAttribute($this->getAttribute($context["url"], "_url", array()), "module", array()) == $this->getContext($context, "currentModule")) && (twig_test_empty($this->getAttribute($this->getAttribute($context["url"], "_url", array()), "action", array())) || ($this->getAttribute($this->getAttribute($context["url"], "_url", array()), "action", array()) == $this->getContext($context, "currentAction"))))) {
                                    echo "class='active'";
                                }
                                echo ">";
                                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array($context["sname"])), "html", null, true);
                                echo "</a>
                                        </li>
                                    ";
                            }
                            // line 18
                            echo "                                ";
                        }
                        $_parent = $context['_parent'];
                        unset($context['_seq'], $context['_iterated'], $context['sname'], $context['url'], $context['_parent'], $context['loop']);
                        $context = array_intersect_key($context, $_parent) + $_parent;
                        // line 19
                        echo "                            </ul>
                        </li>
                    ";
                    }
                    // line 22
                    echo "                ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['name'], $context['submenu'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 23
                echo "            </ul>
        </div>
    ";
            }
        } catch (Exception $e) {
            ob_end_clean();

            throw $e;
        }

        return ('' === $tmp = ob_get_clean()) ? '' : new Twig_Markup($tmp, $this->env->getCharset());
    }

    public function getTemplateName()
    {
        return "@CoreHome/macros.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  98 => 23,  92 => 22,  87 => 19,  81 => 18,  71 => 15,  66 => 13,  63 => 12,  60 => 11,  56 => 10,  51 => 8,  48 => 7,  45 => 6,  41 => 5,  37 => 3,  34 => 2,  21 => 1,);
    }
}
