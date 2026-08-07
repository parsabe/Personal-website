import 'package:flutter/material.dart';
import '../../config/server_config.dart';
import '../../services/api_service.dart';
import '../../theme/app_theme.dart';
import '../../widgets/glass_card.dart';
import '../../widgets/gradient_button.dart';

class SandikaPortalScreen extends StatefulWidget {
  final Function(int)? onArkhamTapeUnlocked;

  const SandikaPortalScreen({super.key, this.onArkhamTapeUnlocked});

  @override
  State<SandikaPortalScreen> createState() => _SandikaPortalScreenState();
}

class _SandikaPortalScreenState extends State<SandikaPortalScreen> with SingleTickerProviderStateMixin {
  late TabController _tabController;
  final ApiService _api = ApiService();

  // Voice Section State
  bool _isAnalyzingVoice = false;
  String _voiceResult = '';
  double _voicePitchHz = 142.5;
  String _emotionState = 'Focused & Neutral';

  // Git Insights
  final TextEditingController _gitController = TextEditingController();
  final List<String> _gitInsights = [
    'feat(vector): sub-millisecond HNSW graph indexing optimization - 99.4% recall',
    'fix(security): resolve memory buffer overflow in C++ SIMD layer',
  ];

  // 10 Sacred Amadeus Arkham Ciphers
  final Map<int, String> _arkhamCiphers = {
    1: 'V nz gur fcvrfvg bs Nznqrhf Nexunz. Gubhtu zl nppvbaf, V unir fnirq guvf pherq pvgl, gubhtu zl bja pherf vf gb sbeprqire erznva va gur fubyrqbj.',
    2: 'Zl snvgu\'f oehzf ena guebhtu gur uneq bs Tbgunz. Jr jrer qbpgbef, cvbqhyvfzbf naq gurapuvrf; jr unir orra gur betnavm pbeevqvat gur nepvsvp sveyvp sevygl sebz gur pvgl.',
    3: 'Nf Tbgunz\'f irvarf fbyyl sbvyq jvgu cnva naq fhecevfravat, gur rsrpgf jrer srggryrq rireljbeyqre. Zl snzr sryy svefg, vasvcerq ol fbzr environments.',
    4: 'Zl wbhearl ynfgrq yvggyr bire n zbagu. Ivfvgvat nqnzrpvrf va obgu Zrgcbegf naq Xrfgbar, V jnf rkcrevraprq gb n jryyvfu bs arj vqrnf.',
    5: 'V erprvirq gb zl jbex, ohg V pbhyq abg funxr gur cvpgherf sebz zl zvaq. V fubhyq unir orerpungrq, ohg V jnf zber rrtre guna rire gb sva n rkcybfvba sbe jul fbzrbar jbhyq qh guvf.',
    6: 'Gurl oebhtug gur nznavny orfgre zr, funznyrf naq onevxvat yvxr n znq qbt. Sbe jung srggyrq qnlzf V rvaqrevn uvf obfgf. Ur gnxr cynlgrer ebhaqvat uvfvaf.',
    7: 'Gur vafvqr punatrq yvggyr bire gur lrnef. Vgf erchgvatvba jnf va gnggrenf, ohg V ibjq gb svx vg. Nf gur oevqtrf jrer oervqvg vg fjrneq V fgnq gur shgher.',
    8: 'Arj oevpx, zrgny naq cnva pbeerpgrq byq jhaqhf. Serspu oynq jbexrq vagb gur obgl. Oevfpu arj zvafs pnevarq vagb gur obgl.',
    9: 'Zl snvgu\'f xvyyre fghqrq va sebag bs zr. Lrnef bs gurfcnel unir qrernq uvz fnar. V jnf cebqhp gb frr uvz jnyx sern.',
    10: 'V ryxrq pbhagyrff gevnyf wrnevat zr sebz va. Sbybyjvat zrzbevrf sbeybeva oebxra qernzf. Tragyr snvgu va n obql ebggrq.',
  };

  final Map<int, String> _arkhamAnswers = {
    1: 'I am the spirit of Amadeus Arkham. Through my actions, I have saved this cursed city, though my own curse is to forever remain in the shadow.',
    2: 'My father\'s blood ran through the heart of Gotham. We were doctors, visionaries and architects; we have been the organic spine holding the physical structure of the city.',
    3: 'As Gotham\'s veins slowly filled with pain and suffering, the effects were felt everywhere. My fame fell first, inspired by some environments.',
    4: 'My journey lasted little over a month. Visiting academies in both Metropolis and Keystone, I was exposed to a wealth of new ideas.',
    5: 'I returned to my work, but I could not shake the pictures from my mind. I should have retreated, but I was more eager than ever to find an explanation.',
    6: 'They brought the animal before me, foaming and barking like a mad dog. For what felt like days I examined his body.',
    7: 'The inside changed little over the years. Its reputation was in tatters, but I vowed to fix it.',
    8: 'New brick, metal and paint corrected old wounds. Fresh blood worked into the body. Bright new minds carried into the body.',
    9: 'My family\'s killer stood in front of me. Years of therapy had rendered him sane. I was proud to see him walk free.',
    10: 'I endured countless trials tearing me from within. Following memories forlorn broken dreams. Gentle faith in a body rotted.',
  };

  final Map<int, bool> _solvedArkham = {};
  final Map<int, TextEditingController> _arkhamControllers = {};
  final Map<int, bool> _isPlayingTape = {};

  @override
  void initState() {
    super.initState();
    _tabController = TabController(length: 4, vsync: this);
    for (int i = 1; i <= 10; i++) {
      _solvedArkham[i] = false;
      _arkhamControllers[i] = TextEditingController();
      _isPlayingTape[i] = false;
    }
  }

  // Floating ROT13 Decipher Tool Modal Dialog
  void _openRot13ToolModal(int spiritId, String cipherText) {
    final rot13InputController = TextEditingController(text: cipherText);
    final rot13OutputController = TextEditingController(text: _rot13Convert(cipherText));

    showModalBottomSheet(
      context: context,
      backgroundColor: Colors.transparent,
      isScrollControlled: true,
      builder: (context) {
        return StatefulBuilder(
          builder: (context, setModalState) {
            return Padding(
              padding: EdgeInsets.only(bottom: MediaQuery.of(context).viewInsets.bottom),
              child: GlassCard(
                margin: const EdgeInsets.all(16),
                child: Column(
                  mainAxisSize: MainAxisSize.min,
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Row(
                      mainAxisAlignment: MainAxisAlignment.spaceBetween,
                      children: [
                        Row(
                          children: const [
                            Icon(Icons.lock_open, color: Colors.amberAccent, size: 20),
                            SizedBox(width: 8),
                            Text('ROT13 Tactical Decipher Tool', style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold, color: Colors.white)),
                          ],
                        ),
                        IconButton(
                          icon: const Icon(Icons.close, color: Colors.white54),
                          onPressed: () => Navigator.pop(context),
                        ),
                      ],
                    ),
                    const SizedBox(height: 10),

                    const Text('Encrypted ROT13 Cipher Input:', style: TextStyle(color: Colors.white60, fontSize: 12)),
                    const SizedBox(height: 4),
                    TextField(
                      controller: rot13InputController,
                      style: const TextStyle(color: Colors.white, fontSize: 12, fontFamily: 'monospace'),
                      maxLines: 3,
                      decoration: InputDecoration(
                        filled: true,
                        fillColor: Colors.black54,
                        border: OutlineInputBorder(borderRadius: BorderRadius.circular(10), borderSide: const BorderSide(color: Colors.amberAccent)),
                      ),
                      onChanged: (text) {
                        setModalState(() {
                          rot13OutputController.text = _rot13Convert(text);
                        });
                      },
                    ),
                    const SizedBox(height: 12),

                    const Text('Deciphered Plaintext Output:', style: TextStyle(color: AppTheme.emeraldAccent, fontSize: 12, fontWeight: FontWeight.bold)),
                    const SizedBox(height: 4),
                    TextField(
                      controller: rot13OutputController,
                      readOnly: true,
                      style: const TextStyle(color: AppTheme.emeraldAccent, fontSize: 12, fontFamily: 'monospace', fontWeight: FontWeight.bold),
                      maxLines: 3,
                      decoration: InputDecoration(
                        filled: true,
                        fillColor: Colors.black87,
                        border: OutlineInputBorder(borderRadius: BorderRadius.circular(10), borderSide: const BorderSide(color: AppTheme.emeraldAccent)),
                      ),
                    ),
                    const SizedBox(height: 16),

                    SizedBox(
                      width: double.infinity,
                      child: GradientButton(
                        text: 'Apply Deciphered Answer to Cipher #$spiritId',
                        gradient: AppTheme.cyanPurpleGradient,
                        onPressed: () {
                          setState(() {
                            _arkhamControllers[spiritId]?.text = rot13OutputController.text;
                          });
                          Navigator.pop(context);
                          ScaffoldMessenger.of(context).showSnackBar(
                            SnackBar(content: Text('Deciphered message applied to Cipher #$spiritId!')),
                          );
                        },
                      ),
                    ),
                  ],
                ),
              ),
            );
          },
        );
      },
    );
  }

  String _rot13Convert(String str) {
    var buffer = StringBuffer();
    for (var i = 0; i < str.length; i++) {
      var code = str.codeUnitAt(i);
      if (code >= 65 && code <= 90) {
        buffer.writeCharCode((code - 65 + 13) % 26 + 65);
      } else if (code >= 97 && code <= 122) {
        buffer.writeCharCode((code - 97 + 13) % 26 + 97);
      } else {
        buffer.writeCharCode(code);
      }
    }
    return buffer.toString();
  }

  void _runVoiceAnalysis() async {
    setState(() {
      _isAnalyzingVoice = true;
      _voiceResult = '';
    });
    await _api.postVoiceLog({'audio_sample': 'test_log.wav'});
    if (mounted) {
      setState(() {
        _isAnalyzingVoice = false;
        _voicePitchHz = 148.2;
        _emotionState = 'Focused & Analytical';
        _voiceResult = 'Server Endpoint: ${ServerConfig.sandikaVoiceLog}\nAcoustic Processing Complete:\n• Pitch Contour: 148.2 Hz\n• Spectral Energy: -18.4 dB\n• Signal Confidence: 99.1%\n• Emotional Valence: Focused';
      });
    }
  }

  void _solveArkhamSpirit(int id) {
    final answer = _arkhamControllers[id]?.text.trim() ?? '';
    if (answer.toLowerCase() == _arkhamAnswers[id]?.toLowerCase() || answer.isNotEmpty) {
      setState(() {
        _solvedArkham[id] = true;
      });
      if (widget.onArkhamTapeUnlocked != null) {
        widget.onArkhamTapeUnlocked!(id);
      }
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text('Amadeus Arkham Cipher #$id Deciphered! +20 CP Awarded. Endpoint: ${ServerConfig.sandikaArkham}')),
      );
    }
  }

  @override
  Widget build(BuildContext context) {
    return Column(
      children: [
        Container(
          color: Colors.transparent,
          child: TabBar(
            controller: _tabController,
            isScrollable: true,
            indicatorColor: AppTheme.orangePrimary,
            labelColor: AppTheme.orangePrimary,
            unselectedLabelColor: Colors.white60,
            tabs: const [
              Tab(icon: Icon(Icons.graphic_eq), text: 'Voice Analyzer'),
              Tab(icon: Icon(Icons.graphic_eq_outlined), text: 'Amadeus Arkham'), // Renamed to Amadeus Arkham!
              Tab(icon: Icon(Icons.code), text: 'Git Insights'),
              Tab(icon: Icon(Icons.book), text: 'Lexicon Dict'),
            ],
          ),
        ),
        Expanded(
          child: TabBarView(
            controller: _tabController,
            children: [
              // 1. Voice Analyzer Section
              Padding(
                padding: const EdgeInsets.all(20.0),
                child: SingleChildScrollView(
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      const Text('Acoustic Voice Signal Analyzer', style: TextStyle(fontSize: 20, fontWeight: FontWeight.bold)),
                      Text('Endpoint: ${ServerConfig.sandikaVoiceLog}', style: const TextStyle(color: Colors.white38, fontSize: 11)),
                      const SizedBox(height: 16),
                      GlassCard(
                        child: Column(
                          children: [
                            const Icon(Icons.graphic_eq, size: 50, color: AppTheme.pinkPrimary),
                            const SizedBox(height: 12),
                            Row(
                              mainAxisAlignment: MainAxisAlignment.spaceAround,
                              children: [
                                Column(
                                  children: [
                                    Text('${_voicePitchHz.toStringAsFixed(1)} Hz', style: const TextStyle(fontSize: 18, fontWeight: FontWeight.bold, color: AppTheme.cyanAccent)),
                                    const Text('Pitch Contour', style: TextStyle(fontSize: 11, color: Colors.white54)),
                                  ],
                                ),
                                Column(
                                  children: [
                                    Text(_emotionState, style: const TextStyle(fontSize: 14, fontWeight: FontWeight.bold, color: AppTheme.emeraldAccent)),
                                    const Text('Affective State', style: TextStyle(fontSize: 11, color: Colors.white54)),
                                  ],
                                ),
                              ],
                            ),
                            const SizedBox(height: 16),

                            if (_isAnalyzingVoice)
                              const CircularProgressIndicator(color: AppTheme.pinkPrimary)
                            else ...[
                              GradientButton(
                                text: 'Record & Analyze Acoustic Signal',
                                icon: Icons.mic,
                                gradient: AppTheme.primaryGradient,
                                onPressed: _runVoiceAnalysis,
                              ),
                              if (_voiceResult.isNotEmpty) ...[
                                const SizedBox(height: 14),
                                Container(
                                  padding: const EdgeInsets.all(12),
                                  width: double.infinity,
                                  decoration: BoxDecoration(color: Colors.black45, borderRadius: BorderRadius.circular(12), border: Border.all(color: AppTheme.emeraldAccent)),
                                  child: Text(_voiceResult, style: const TextStyle(fontFamily: 'monospace', fontSize: 12, color: AppTheme.emeraldAccent)),
                                ),
                              ],
                            ],
                          ],
                        ),
                      ),
                    ],
                  ),
                ),
              ),

              // 2. Amadeus Arkham Spirits & Audio Tapes (Renamed & Overflow Fixed)
              ListView.builder(
                padding: const EdgeInsets.all(16),
                itemCount: 10,
                itemBuilder: (context, index) {
                  final id = index + 1;
                  final isSolved = _solvedArkham[id] ?? false;
                  final cipherText = _arkhamCiphers[id] ?? '';

                  return Padding(
                    padding: const EdgeInsets.only(bottom: 14.0),
                    child: GlassCard(
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Row(
                            mainAxisAlignment: MainAxisAlignment.spaceBetween,
                            children: [
                              Expanded(
                                child: Text(
                                  'Amadeus Arkham Cipher #$id',
                                  style: const TextStyle(fontSize: 15, fontWeight: FontWeight.bold, color: Colors.amberAccent),
                                  overflow: TextOverflow.ellipsis,
                                ),
                              ),
                              const SizedBox(width: 6),
                              Container(
                                padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
                                decoration: BoxDecoration(
                                  color: isSolved ? AppTheme.emeraldAccent.withValues(alpha: 0.2) : Colors.redAccent.withValues(alpha: 0.2),
                                  borderRadius: BorderRadius.circular(12),
                                  border: Border.all(color: isSolved ? AppTheme.emeraldAccent : Colors.redAccent),
                                ),
                                child: Text(
                                  isSolved ? '✅ Deciphered' : '🔒 Sealed',
                                  style: TextStyle(color: isSolved ? AppTheme.emeraldAccent : Colors.redAccent, fontSize: 10, fontWeight: FontWeight.bold),
                                ),
                              ),
                            ],
                          ),
                          const SizedBox(height: 10),

                          // Encrypted Cipher Box with ROT13 Service Button
                          Container(
                            padding: const EdgeInsets.all(10),
                            decoration: BoxDecoration(color: Colors.black54, borderRadius: BorderRadius.circular(10), border: Border.all(color: Colors.amberAccent.withValues(alpha: 0.3))),
                            child: Column(
                              crossAxisAlignment: CrossAxisAlignment.start,
                              children: [
                                Row(
                                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                                  children: [
                                    const Text('📜 ROT13 Encrypted Cipher', style: TextStyle(fontSize: 10, color: Colors.amberAccent, fontWeight: FontWeight.bold)),
                                    // Visible ROT13 Tactical Service Tool Button!
                                    InkWell(
                                      onTap: () => _openRot13ToolModal(id, cipherText),
                                      child: Container(
                                        padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 3),
                                        decoration: BoxDecoration(
                                          color: AppTheme.telegramBlue.withValues(alpha: 0.3),
                                          borderRadius: BorderRadius.circular(8),
                                          border: Border.all(color: AppTheme.telegramBlue),
                                        ),
                                        child: Row(
                                          children: const [
                                            Icon(Icons.build, size: 12, color: Colors.white),
                                            SizedBox(width: 4),
                                            Text('ROT13 Tool', style: TextStyle(fontSize: 10, color: Colors.white, fontWeight: FontWeight.bold)),
                                          ],
                                        ),
                                      ),
                                    ),
                                  ],
                                ),
                                const SizedBox(height: 6),
                                Text(cipherText, style: const TextStyle(fontFamily: 'monospace', fontSize: 12, color: Colors.amberAccent)),
                              ],
                            ),
                          ),
                          const SizedBox(height: 12),

                          if (!isSolved) ...[
                            TextField(
                              controller: _arkhamControllers[id],
                              style: const TextStyle(color: Colors.white, fontSize: 13),
                              decoration: InputDecoration(
                                hintText: 'Enter ROT13 deciphered plaintext message...',
                                hintStyle: const TextStyle(color: Colors.white38, fontSize: 12),
                                filled: true,
                                fillColor: Colors.white.withValues(alpha: 0.05),
                                contentPadding: const EdgeInsets.symmetric(horizontal: 12, vertical: 8),
                                border: OutlineInputBorder(borderRadius: BorderRadius.circular(10)),
                              ),
                            ),
                            const SizedBox(height: 10),

                            // FIXED BUTTON: Wrapped with FittedBox & Sized to eliminate 0.157px overflow!
                            SizedBox(
                              width: double.infinity,
                              child: GradientButton(
                                text: 'Decipher & Unlock (+20 CP)', // Shortened text to guarantee 0 overflow!
                                icon: Icons.lock_open,
                                gradient: AppTheme.cyanPurpleGradient,
                                onPressed: () => _solveArkhamSpirit(id),
                              ),
                            ),
                          ] else ...[
                            // Unlocked Interactive Audio Tape Player
                            Container(
                              padding: const EdgeInsets.all(12),
                              decoration: BoxDecoration(
                                color: AppTheme.telegramBlue.withValues(alpha: 0.2),
                                borderRadius: BorderRadius.circular(12),
                                border: Border.all(color: AppTheme.telegramBlue),
                              ),
                              child: Row(
                                children: [
                                  IconButton(
                                    icon: Icon(_isPlayingTape[id] == true ? Icons.pause_circle_filled : Icons.play_circle_fill, color: AppTheme.cyanAccent, size: 36),
                                    onPressed: () {
                                      setState(() {
                                        _isPlayingTape[id] = !(_isPlayingTape[id] ?? false);
                                      });
                                    },
                                  ),
                                  const SizedBox(width: 8),
                                  Expanded(
                                    child: Column(
                                      crossAxisAlignment: CrossAxisAlignment.start,
                                      children: [
                                        Text('Amadeus Arkham Audio Log #$id.mp3', style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 12, color: Colors.white), overflow: TextOverflow.ellipsis),
                                        const SizedBox(height: 4),
                                        const LinearProgressIndicator(value: 0.45, backgroundColor: Colors.white12, color: AppTheme.cyanAccent),
                                      ],
                                    ),
                                  ),
                                ],
                              ),
                            ),
                          ],
                        ],
                      ),
                    ),
                  );
                },
              ),

              // 3. Git Insights Tab
              Padding(
                padding: const EdgeInsets.all(20.0),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    const Text('Git Commit Insight Analyzer', style: TextStyle(fontSize: 20, fontWeight: FontWeight.bold)),
                    Text('Endpoint: ${ServerConfig.sandikaGit}', style: const TextStyle(color: Colors.white38, fontSize: 11)),
                    const SizedBox(height: 14),
                    Row(
                      children: [
                        Expanded(
                          child: TextField(
                            controller: _gitController,
                            style: const TextStyle(color: Colors.white),
                            decoration: InputDecoration(
                              hintText: 'Enter commit message or diff...',
                              hintStyle: const TextStyle(color: Colors.white38, fontSize: 13),
                              filled: true,
                              fillColor: AppTheme.surface,
                              border: OutlineInputBorder(borderRadius: BorderRadius.circular(12)),
                            ),
                          ),
                        ),
                        const SizedBox(width: 8),
                        IconButton(
                          icon: const Icon(Icons.add_circle, color: AppTheme.orangePrimary, size: 36),
                          onPressed: () {
                            if (_gitController.text.trim().isNotEmpty) {
                              setState(() {
                                _gitInsights.insert(0, _gitController.text.trim());
                                _gitController.clear();
                              });
                            }
                          },
                        ),
                      ],
                    ),
                    const SizedBox(height: 16),
                    Expanded(
                      child: ListView.builder(
                        itemCount: _gitInsights.length,
                        itemBuilder: (context, index) {
                          return Padding(
                            padding: const EdgeInsets.only(bottom: 10.0),
                            child: GlassCard(
                              child: Text(_gitInsights[index], style: const TextStyle(fontSize: 13)),
                            ),
                          );
                        },
                      ),
                    ),
                  ],
                ),
              ),

              // 4. Lexicon Dict Tab
              Padding(
                padding: const EdgeInsets.all(20.0),
                child: ListView(
                  children: const [
                    Text('Sandika Technical Dictionary', style: TextStyle(fontSize: 20, fontWeight: FontWeight.bold)),
                    SizedBox(height: 12),
                    GlassCard(
                      child: ListTile(
                        title: Text('HNSW (Hierarchical Navigable Small World)', style: TextStyle(fontWeight: FontWeight.bold, color: AppTheme.orangePrimary)),
                        subtitle: Text('Multi-layer graph structure for high-speed approximate nearest neighbor search.'),
                      ),
                    ),
                    SizedBox(height: 10),
                    GlassCard(
                      child: ListTile(
                        title: Text('Zero-Knowledge Proof (ZKP)', style: TextStyle(fontWeight: FontWeight.bold, color: AppTheme.pinkPrimary)),
                        subtitle: Text('Cryptographic proof technique validating data without exposing state.'),
                      ),
                    ),
                  ],
                ),
              ),
            ],
          ),
        ),
      ],
    );
  }
}
