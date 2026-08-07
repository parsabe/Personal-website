class VpnNode {
  final String id;
  final String country;
  final String city;
  final String flagEmoji;
  final int latencyMs;
  final String ipAddress;
  final double loadPercentage;
  final bool isOnline;

  const VpnNode({
    required this.id,
    required this.country,
    required this.city,
    required this.flagEmoji,
    required this.latencyMs,
    required this.ipAddress,
    required this.loadPercentage,
    required this.isOnline,
  });

  static List<VpnNode> get sampleNodes => [
    const VpnNode(
      id: 'vpn-de-1',
      country: 'Germany',
      city: 'Frankfurt',
      flagEmoji: '🇩🇪',
      latencyMs: 14,
      ipAddress: '185.220.101.5',
      loadPercentage: 35.0,
      isOnline: true,
    ),
    const VpnNode(
      id: 'vpn-de-2',
      country: 'Germany',
      city: 'Leipzig / Freiberg Node',
      flagEmoji: '🇩🇪',
      latencyMs: 8,
      ipAddress: '141.30.220.12',
      loadPercentage: 22.5,
      isOnline: true,
    ),
    const VpnNode(
      id: 'vpn-nl-1',
      country: 'Netherlands',
      city: 'Amsterdam',
      flagEmoji: '🇳🇱',
      latencyMs: 24,
      ipAddress: '95.211.198.42',
      loadPercentage: 48.0,
      isOnline: true,
    ),
    const VpnNode(
      id: 'vpn-us-1',
      country: 'United States',
      city: 'New York',
      flagEmoji: '🇺🇸',
      latencyMs: 95,
      ipAddress: '198.51.100.88',
      loadPercentage: 62.0,
      isOnline: true,
    ),
    const VpnNode(
      id: 'vpn-ch-1',
      country: 'Switzerland',
      city: 'Zurich Secure Node',
      flagEmoji: '🇨🇭',
      latencyMs: 31,
      ipAddress: '179.43.140.11',
      loadPercentage: 18.0,
      isOnline: true,
    ),
  ];
}
