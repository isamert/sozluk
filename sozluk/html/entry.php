<ul>
    <?php
        $entry = Entry::get_entry($_GET['entry_id']);
        $member = Member::get_member($entry['member_id']);
        include("html/single_entry.php");
    ?>
</ul>