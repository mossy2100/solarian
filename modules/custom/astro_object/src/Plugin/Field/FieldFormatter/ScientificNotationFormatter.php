<?php
/**
 * @author Shaun Moss (shaunmoss71@gmail.com)
 * @date 2018-03-02
 * @file
 * Format a large number using scientific notation.
 */

namespace Drupal\astro_object\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\Plugin\Field\FieldFormatter\DecimalFormatter;
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin implementation of the scientific notation formatter.
 *
 * @FieldFormatter(
 *   id = "scientific_notation",
 *   label = @Translation("Scientific notation"),
 *   field_types = {
 *     "float",
 *     "computed_float"
 *   }
 * )
 */
class ScientificNotationFormatter extends DecimalFormatter {

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    $elements = parent::settingsForm($form, $form_state);

    // Remove support for thousands separators, since the number will always be displayed with only one digit before the
    // decimal pount, e.g. 9.999e99
    // Thousandths separators are a separate issue, not likely to be important.
    unset($elements['thousand_separator']);

    return $elements;
  }

  /**
   * {@inheritdoc}
   */
  protected function numberFormat($number) {
    $format_str = '%.' . $this->getSetting('scale') . 'e';
    $sci = sprintf($format_str, $number);
    $parts = explode('e', $sci);
    $output = str_replace('.', $this->getSetting('decimal_separator'), $parts[0]) . '&times;10<sup>' . ltrim($parts[1], '+') . '</sup>';
    return $output;
  }

}
