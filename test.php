<?php
require_once 'functions.php';
require_once 'category.php';
require_once 'topic.php';
require_once 'entry.php';
require_once 'member/member.php';

$cat1_id = Category::add("CATEGORY1", "CATEGORY1 description");
$cat2_id = Category::add("CATEGORY2", "CATEGORY2 description");


$topic1_id = Topic::add("TOPIC1-cat1", $cat1_id);
$topic2_id = Topic::add("TOPIC2-cat1", $cat1_id);
$topic3_id = Topic::add("TOPIC3-cat1", $cat1_id);
$topic4_id = Topic::add("TOPIC4-cat2", $cat2_id);
$topic5_id = Topic::add("TOPIC5-cat2", $cat2_id);

$member1_id = Member::add("3131sssamert3131", "isamertpassw323d", "zaaxd1@gmail.com", "isa mert ahaaaa", "e");


$entry1_id = Entry::add_topic($member1_id, $topic1_id, "entry1 content goes here");
$entry2_id = Entry::add_topic($member1_id, $topic1_id, "entry2 content goes here");
$entry3_id = Entry::add_topic($member1_id, $topic1_id, "entry3 content goes here");
$entry4_id = Entry::add_topic($member1_id, $topic2_id, "entry4 content goes here");
$entry5_id = Entry::add_topic($member1_id, $topic3_id, "entry5 content goes here");
?>