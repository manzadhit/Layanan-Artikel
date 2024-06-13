<?php

function getFirstTagRegex($content)
{
  preg_match('/<p>(.*?)<\/p>/s', $content, $matches);
  return $matches[1];
}
