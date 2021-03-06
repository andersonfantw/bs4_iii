<?php

/* @Annotations/getEvolutionIcons.twig */
class __TwigTemplate_3266e9d5a68497ce7ccd73f5255c1451c73ee3f24408254b67780904579b7550 extends Twig_Template
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
        echo "<div class=\"evolution-annotations\">
    ";
        // line 2
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable($this->getContext($context, "annotationCounts"));
        foreach ($context['_seq'] as $context["_key"] => $context["dateCountPair"]) {
            // line 3
            echo "        ";
            $context["date"] = $this->getAttribute($context["dateCountPair"], 0, array(), "array");
            // line 4
            echo "        ";
            $context["counts"] = $this->getAttribute($context["dateCountPair"], 1, array(), "array");
            // line 5
            echo "        <span data-date=\"";
            echo twig_escape_filter($this->env, $this->getContext($context, "date"), "html", null, true);
            echo "\" data-count=\"";
            echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, "counts"), "count", array()), "html", null, true);
            echo "\" data-starred=\"";
            echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, "counts"), "starred", array()), "html", null, true);
            echo "\"
              ";
            // line 6
            if (($this->getAttribute($this->getContext($context, "counts"), "count", array()) == 0)) {
                echo "title=\"";
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("Annotations_AddAnnotationsFor", $this->getContext($context, "date"))), "html", null, true);
                echo "\"
              ";
            } elseif (($this->getAttribute(            // line 7
$this->getContext($context, "counts"), "count", array()) == 1)) {
                echo "title=\"";
                echo call_user_func_array($this->env->getFilter('translate')->getCallable(), array("Annotations_AnnotationOnDate", $this->getContext($context, "date"), $this->getAttribute(                // line 8
$this->getContext($context, "counts"), "note", array())));
                echo "
";
                // line 9
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("Annotations_ClickToEditOrAdd")), "html", null, true);
                echo "\"
              ";
            } else {
                // line 10
                echo "}title=\"";
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("Annotations_ViewAndAddAnnotations", $this->getContext($context, "date"))), "html", null, true);
                echo "\"";
            }
            echo ">
            <img src=\"plugins/Morpheus/images/";
            // line 11
            if (($this->getAttribute($this->getContext($context, "counts"), "starred", array()) > 0)) {
                echo "annotations_starred.png";
            } else {
                echo "annotations.png";
            }
            echo "\" width=\"16\" height=\"16\"/>
        </span>
    ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['dateCountPair'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 14
        echo "</div>
";
    }

    public function getTemplateName()
    {
        return "@Annotations/getEvolutionIcons.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  79 => 14,  66 => 11,  59 => 10,  54 => 9,  50 => 8,  47 => 7,  41 => 6,  32 => 5,  29 => 4,  26 => 3,  22 => 2,  19 => 1,);
    }
}
