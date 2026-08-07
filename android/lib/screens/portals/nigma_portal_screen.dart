import 'package:flutter/material.dart';
import '../../config/server_config.dart';
import '../../services/api_service.dart';
import '../../theme/app_theme.dart';
import '../../widgets/glass_card.dart';
import '../../widgets/gradient_button.dart';

class NigmaPortalScreen extends StatefulWidget {
  const NigmaPortalScreen({super.key});

  @override
  State<NigmaPortalScreen> createState() => _NigmaPortalScreenState();
}

class _NigmaPortalScreenState extends State<NigmaPortalScreen> {
  final ApiService _api = ApiService();
  int _currentRiddleIndex = 0;
  final TextEditingController _answerController = TextEditingController();
  bool _showHint = false;
  String _feedbackMessage = '';
  int _score = 0;

  final List<Map<String, String>> _riddles = [
    {
      'id': 'r1',
      'question': 'Riddle #1: I encrypt data using non-reversible mathematical hashing functions with fixed length outputs. What algorithm family am I?',
      'hint': 'Starts with "SHA" or "MD"...',
      'answer': 'hash',
    },
    {
      'id': 'r2',
      'question': 'Riddle #2: I let you communicate across untrusted networks while proving your identity using public and private key pairs. What cryptosystem am I?',
      'hint': 'Named after Rivest, Shamir, and Adleman.',
      'answer': 'rsa',
    },
    {
      'id': 'r3',
      'question': 'Riddle #3: I search through high-dimensional vector spaces in O(log N) time using graph layer hops. What index structure am I?',
      'hint': 'Four letter abbreviation used in Vectra DB (HNSW).',
      'answer': 'hnsw',
    },
  ];

  void _checkAnswer() async {
    final riddle = _riddles[_currentRiddleIndex];
    final userAnswer = _answerController.text.trim().toLowerCase();

    final res = await _api.solveNigmaRiddle(riddle['id']!, userAnswer);

    if (userAnswer.contains(riddle['answer']!)) {
      setState(() {
        _score += (res['points'] as int? ?? 100);
        _feedbackMessage = '🎉 Correct! +100 Points! Verified by Endpoint: ${ServerConfig.nigmaSolve}';
      });
      Future.delayed(const Duration(seconds: 1), () {
        if (mounted && _currentRiddleIndex < _riddles.length - 1) {
          setState(() {
            _currentRiddleIndex++;
            _answerController.clear();
            _showHint = false;
            _feedbackMessage = '';
          });
        }
      });
    } else {
      setState(() {
        _feedbackMessage = '❌ Incorrect solution. Try again or view hint!';
      });
    }
  }

  @override
  Widget build(BuildContext context) {
    final currentRiddle = _riddles[_currentRiddleIndex];

    return SingleChildScrollView(
      physics: const BouncingScrollPhysics(parent: AlwaysScrollableScrollPhysics()),
      padding: const EdgeInsets.fromLTRB(20, 20, 20, 120),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: const [
                  Text('Nigma Riddles Portal', style: TextStyle(fontSize: 22, fontWeight: FontWeight.bold)),
                  Text('Subdomain: ${ServerConfig.nigmaSubdomain}', style: TextStyle(color: Colors.white38, fontSize: 11)),
                ],
              ),
              Container(
                padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 6),
                decoration: BoxDecoration(color: AppTheme.orangePrimary.withValues(alpha: 0.2), borderRadius: BorderRadius.circular(16)),
                child: Text('Score: $_score', style: const TextStyle(color: AppTheme.orangePrimary, fontWeight: FontWeight.bold, fontSize: 14)),
              ),
            ],
          ),
          const SizedBox(height: 20),

          GlassCard(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Row(
                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                  children: [
                    Text('Challenge ${_currentRiddleIndex + 1} of ${_riddles.length}', style: const TextStyle(color: AppTheme.cyanAccent, fontWeight: FontWeight.bold, fontSize: 12)),
                    const Icon(Icons.help_outline, color: AppTheme.pinkPrimary),
                  ],
                ),
                const SizedBox(height: 12),
                Text(currentRiddle['question']!, style: const TextStyle(fontSize: 16, fontWeight: FontWeight.w600, height: 1.4)),
                const SizedBox(height: 16),

                if (_showHint) ...[
                  Container(
                    padding: const EdgeInsets.all(10),
                    width: double.infinity,
                    decoration: BoxDecoration(color: Colors.amber.withValues(alpha: 0.1), borderRadius: BorderRadius.circular(10), border: Border.all(color: Colors.amber.withValues(alpha: 0.4))),
                    child: Text('💡 Hint: ${currentRiddle['hint']}', style: const TextStyle(color: Colors.amber, fontSize: 13)),
                  ),
                  const SizedBox(height: 14),
                ],

                TextField(
                  controller: _answerController,
                  style: const TextStyle(color: Colors.white),
                  decoration: InputDecoration(
                    hintText: 'Enter your answer...',
                    hintStyle: const TextStyle(color: Colors.white38, fontSize: 14),
                    filled: true,
                    fillColor: Colors.black26,
                    border: OutlineInputBorder(borderRadius: BorderRadius.circular(12)),
                  ),
                ),
                const SizedBox(height: 14),

                Row(
                  children: [
                    OutlinedButton.icon(
                      icon: const Icon(Icons.lightbulb_outline, size: 18),
                      label: Text(_showHint ? 'Hide Hint' : 'Show Hint'),
                      onPressed: () {
                        setState(() {
                          _showHint = !_showHint;
                        });
                      },
                    ),
                    const Spacer(),
                    GradientButton(
                      text: 'Submit Solution',
                      gradient: AppTheme.primaryGradient,
                      onPressed: _checkAnswer,
                    ),
                  ],
                ),

                if (_feedbackMessage.isNotEmpty) ...[
                  const SizedBox(height: 16),
                  Text(_feedbackMessage, style: TextStyle(color: _feedbackMessage.contains('Correct') ? AppTheme.emeraldAccent : Colors.redAccent, fontWeight: FontWeight.bold, fontSize: 12)),
                ],
              ],
            ),
          ),
        ],
      ),
    );
  }
}
