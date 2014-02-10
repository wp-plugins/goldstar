<div class="territory-wrap">
    <div class="territory-list-container" id="territory-list-container">
        <table>
            <thead>
                <tr>
                    <th>Choice ID</th>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Slug</th>
                    <th>Initials</th>
                    <th>Time Zone </th>
                </tr>
            </thead>
            <tbody>
            <?php 
            foreach($arr_data as $i => $arr_item) {
                ?>
                <tr>
                    <td>
                        <input type="radio" name="territory_id" value="<?php echo $arr_item["id"] ?>" 
                        <?php echo ($arr_item['id'] == $territory_id ) ? 'checked="true"' : '' ?>/>
                    </td>
                    <td><?php echo $arr_item['id'] ?></td>
                    <td><?php echo $arr_item['name'] ?></td>
                    <td><?php echo $arr_item['slug'] ?></td>
                    <td><?php echo $arr_item['initials'] ?></td>
                    <td><?php echo $arr_item['timezone'] ?></td>
                </tr>
                <?php
            }
            ?>
            </tbody>
        </table>
    </div>
    <div class="command-container">
        <a href="javascript:void(0);" id="choice-territory-id" class="button button-primary">Select</a>
    </div>
</div>