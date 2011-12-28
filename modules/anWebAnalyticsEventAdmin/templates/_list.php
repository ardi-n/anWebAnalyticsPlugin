<?php 

$filters = $sf_user->getAttribute('anWebAnalyticsEventAdmin.filters', array(), 'admin_module');
$type = isset($filters['type']) ? $filters['type'] : null;

?>

<div class="sf_admin_list">
  <?php if (!$pager->getNbResults()): ?>
    <h2><?php echo __('No result') ?></h2>
  <?php else: ?>
    <table>
      <thead>
        <tr>
          <?php include_partial('anWebAnalyticsEventAdmin/list_th_tabular' . ($type ? '_'.$type:''), array('sort' => $sort)) ?>
        </tr>
      </thead>
      <tfoot>
        <tr>
          <?php include_partial('anWebAnalyticsEventAdmin/list_th_tabular' . ($type ? '_'.$type:''), array('sort' => $sort)) ?>
        </tr>
      </tfoot>
      <tbody class='{toggle_url: "<?php echo £link('@'.$helper->getUrlForAction('toggleBoolean'))->getHref() ?>"}'>
        <?php foreach ($pager->getResults() as $i => $an_web_analytics_event): $odd = fmod(++$i, 2) ? 'odd' : 'even' ?>
          <tr class="sf_admin_row <?php echo $odd ?> {pk: <?php echo $an_web_analytics_event->getPrimaryKey() ?>}">
            <?php include_partial('anWebAnalyticsEventAdmin/list_td_tabular' . ($type ? '_'.$type:''), array('an_web_analytics_event' => $an_web_analytics_event)) ?>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>
</div>