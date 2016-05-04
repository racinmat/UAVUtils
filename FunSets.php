<?php

class FunSets {

	public function contains($set, $elem) {
		return $set($elem);
	}

	public function singletonSet($elem) {
		return function ($otherElem) use ($elem) {
			return $elem == $otherElem;
		};
	}

	public function union($s1, $s2) {
		return function ($otherElem) use ($s1, $s2) {
			return $this->contains($s1, $otherElem) || $this->contains($s2, $otherElem);
		};
	}
}