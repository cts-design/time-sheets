<?php echo (!empty($instructions) ? '<div id="Instructions">' . $instructions . '</div>' : '' ) ?>
<br />
<div>
    <table width="100%">
        <tr>
            <th>Step</th><th>Status</th><th>Actions</th>
        </tr>
        <tr>
            <td><?php echo $program['ProgramStep'][0]['name']?></td>
            <td>Incomplete</td>
            <td><?php echo $html->link('Complete Form', '/program_responses/form/' . $program['ProgramStep'][0]['id'])?></td>
        </tr>
    </table>
</div>
