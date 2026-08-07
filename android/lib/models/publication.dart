class Publication {
  final String id;
  final String title;
  final String category;
  final String abstractText;
  final String fullPaperBody;
  final String methodology;
  final List<String> authors;
  final String date;
  final List<String> keywords;
  final String journal;
  final String department;
  final String contactEmail;

  const Publication({
    required this.id,
    required this.title,
    required this.category,
    required this.abstractText,
    required this.fullPaperBody,
    required this.methodology,
    required this.authors,
    required this.date,
    required this.keywords,
    required this.journal,
    this.department = 'Department of Math and Computer Science, Technische Universität Bergakademie Freiberg, Germany',
    this.contactEmail = 'parsabe99@gmail.com',
  });

  static List<Publication> get samplePublications => [
    const Publication(
      id: 'vectra_paper',
      title: 'Vectra: The Quarantine Matrix, Constraining Neural Hallucinations in 3D Gaussian Environments',
      category: 'AI & Data Science',
      abstractText: 'The hyper-accelerated rise of generative artificial intelligence is rewiring the rules of 3D content creation. Yet, seamlessly jacking everyday 2D inputs into fully interactive, dynamic 3D constructs remains a critical bottleneck. This paper introduces an end-to-end framework engineered to generate, extract, and simulate high-fidelity 3D objects directly from simple visual and textual data.',
      fullPaperBody: '''
1. INTRODUCTION & MOTIVATION
3D asset generation has historically suffered from spatial latency and high computational overhead. By utilizing 3D Gaussian Splatting combined with Neural Rendering pipelines, Vectra constrains hallucinated geometry within a deterministic Quarantine Matrix boundary.

2. SYSTEM ARCHITECTURE
Our pipeline consists of three sequential modules:
a) Zero-Shot Semantic Feature Extraction via Vision-Language Transformers.
b) Generative Mesh & Splatting Synthesis operating on high-dimensional vector embeddings.
c) Real-time WebGL & Physics Integration engine executing at 120 FPS.

3. BENCHMARK RESULTS & METRICS
- Sub-millisecond vector retrieval (0.84 ms average latency across 10 million vectors)
- Peak Signal-to-Noise Ratio (PSNR): 34.2 dB
- Structural Similarity Index (SSIM): 0.965
- GPU Memory Footprint: 2.1 GB VRAM
''',
      methodology: 'HNSW Graph Indexing combined with Product Quantization (PQ) and CUDA GPU Acceleration.',
      authors: ['Parsa Besharat'],
      date: '2025',
      keywords: ['Vectra', 'Spatial Computing', '3D Gaussian Splatting', 'Neural Rendering', 'Generative AI'],
      journal: 'IEEE Transactions on Neural Networks & AI Systems',
    ),
    const Publication(
      id: 'blackwall_paper',
      title: 'Autonomous Cyber Defense via Real-Time Neural Packet Analysis',
      category: 'Cyber Security & AI',
      abstractText: 'We present Blackwall, an autonomous cyber threat mitigation architecture leveraging transformer-based sequence modeling to detect non-linear attack vectors and Zero-Day exploits in high-throughput network backbones.',
      fullPaperBody: '''
1. EXECUTIVE SUMMARY
Traditional Intrusion Detection Systems (IDS) rely heavily on static signature databases. Blackwall replaces signature rules with real-time deep neural sequence modeling directly on raw packet byte streams.

2. NEURAL PIPELINE
- Deep Packet Attention Layer evaluating payload entropy.
- Temporal Anomaly Transformer predicting attack probability within 1.2 ms.
- Autonomous TCP/UDP Connection Isolation & Rate Limiting.
''',
      methodology: 'Transformer-based sequence modeling trained on 500GB of enterprise network traffic logs.',
      authors: ['Parsa Besharat'],
      date: '2025',
      keywords: ['Cyber Security', 'Deep Learning', 'Zero-Day Detection', 'Network Analysis'],
      journal: 'Journal of Cybersecurity & Machine Intelligence',
    ),
    const Publication(
      id: 'moodium',
      title: 'Moodium: Multi-Modal Sentiment & Affective State Detection from Speech Signal Dynamics',
      category: 'Machine Learning',
      abstractText: 'Moodium presents a deep neural architecture for extracting emotional features from continuous speech signals, evaluating prosodic frequency contours and spectral energy distribution.',
      fullPaperBody: '''
1. SPEECH SIGNAL DYNAMICS
Extracting emotional contours requires joint time-frequency domain representations. Moodium calculates Mel-Frequency Cepstral Coefficients (MFCCs) alongside fundamental pitch contours (F0).

2. MODEL ACCURACY
- Emotion Classification Accuracy: 93.4% on RAVDESS benchmark dataset.
- Ultra-low latency voice frame processing (15 ms windowing).
''',
      methodology: 'Bi-directional LSTM with Self-Attention over spectral audio representations.',
      authors: ['Parsa Besharat'],
      date: '2024',
      keywords: ['Speech Analysis', 'Affective Computing', 'Audio Signal Processing'],
      journal: 'International Conference on Machine Learning & Signal Processing',
    ),
    const Publication(
      id: 'scm',
      title: 'Software Configuration Management & Automated Vulnerability Tracking in Enterprise CI/CD',
      category: 'Software Engineering',
      abstractText: 'An empirical investigation into static code analysis integration within DevOps build pipelines, proving a 74% reduction in critical production vulnerabilities across 50 open-source repositories.',
      fullPaperBody: 'Full empirical study on DevOps automated vulnerability discovery and AST sanitization protocols.',
      methodology: 'Static Code Analysis (AST) integrated directly into GitHub Actions & GitLab CI pipelines.',
      authors: ['Parsa Besharat'],
      date: '2024',
      keywords: ['DevOps', 'CI/CD', 'Software Engineering', 'Security Audit'],
      journal: 'Journal of Software Maintenance & Evolution',
    ),
    const Publication(
      id: 'captcha',
      title: 'Adversarial Machine Learning Resilience in Neural CAPTCHA Verification Systems',
      category: 'AI Security',
      abstractText: 'This study evaluates the robustness of vision-based CAPTCHA mechanisms against fast gradient sign method (FGSM) adversarial perturbations and proposes dynamic noise injection protocols.',
      fullPaperBody: 'Detailed analysis of vision transformer vulnerability to adversarial perturbations and dynamic defense mechanisms.',
      methodology: 'FGSM attack simulation & dynamic noise injection layers.',
      authors: ['Parsa Besharat'],
      date: '2024',
      keywords: ['CAPTCHA', 'Adversarial Attacks', 'Computer Vision', 'Security'],
      journal: 'Cryptographic & Machine Learning Security Review',
    ),
    const Publication(
      id: 'ai_blockchain',
      title: 'Decentralized AI Model Governance via Smart Contract Verification Protocols',
      category: 'Blockchain & AI',
      abstractText: 'Combining zero-knowledge proofs (ZK-SNARKs) with Ethereum smart contracts to audit machine learning model weights, ensuring deterministic and tamper-proof AI execution.',
      fullPaperBody: 'Zero-Knowledge proof protocol verifying AI neural network weights on EVM blockchains.',
      methodology: 'ZK-SNARKs circuit generation & Solidity smart contract verification.',
      authors: ['Parsa Besharat'],
      date: '2023',
      keywords: ['Blockchain', 'Zero-Knowledge Proofs', 'Model Auditability', 'Smart Contracts'],
      journal: 'IEEE Access - Blockchain & Distributed Ledger Tech',
    ),
    const Publication(
      id: 'synergy_blockchain',
      title: 'Synergy Blockchain: High-Throughput Consensus for IoT Edge Data Exchange',
      category: 'Blockchain',
      abstractText: 'Synergy Blockchain introduces a Directed Acyclic Graph (DAG) consensus mechanism tailored for resource-constrained Internet of Things (IoT) sensors, achieving 15,000 TPS.',
      fullPaperBody: 'DAG consensus architecture optimized for low-power edge nodes.',
      methodology: 'Directed Acyclic Graph (DAG) consensus with asynchronous Byzantine Fault Tolerance.',
      authors: ['Parsa Besharat'],
      date: '2023',
      keywords: ['Consensus Protocols', 'IoT', 'DAG', 'Distributed Systems'],
      journal: 'International Journal of Distributed Systems',
    ),
    const Publication(
      id: 'php_vuls',
      title: 'Automated Static Analysis of Security Vulnerabilities in Dynamic Web Applications',
      category: 'Web Security',
      abstractText: 'A comprehensive study of SQL injection, XSS, and remote code execution vulnerabilities in dynamic server-side scripts, proposing automated AST sanitization algorithms.',
      fullPaperBody: 'AST sanitization algorithms detecting SQLi and XSS in dynamic PHP applications.',
      methodology: 'Abstract Syntax Tree (AST) static taint analysis.',
      authors: ['Parsa Besharat'],
      date: '2023',
      keywords: ['Web Security', 'AST Analysis', 'Static Analysis', 'PHP Security'],
      journal: 'Cybersecurity & Software Defense Letters',
    ),
    const Publication(
      id: 'crm',
      title: 'Predictive Customer Churn Analytics Using Gradient Boosted Decision Trees',
      category: 'Data Science',
      abstractText: 'Evaluating XGBoost, LightGBM, and CatBoost models on enterprise CRM interaction logs, demonstrating 92.8% AUC accuracy in identifying early churn indicators.',
      fullPaperBody: 'Comparative study of ensemble tree models for customer retention predictive analytics.',
      methodology: 'XGBoost & CatBoost ensemble training on 500,000 CRM interaction logs.',
      authors: ['Parsa Besharat'],
      date: '2023',
      keywords: ['Data Science', 'Customer Churn', 'Gradient Boosting', 'CRM'],
      journal: 'Applied Intelligence & Business Analytics',
    ),
    const Publication(
      id: 'qca',
      title: 'Quantum Cellular Automata Simulation for Low-Power Nano-Scale Circuit Design',
      category: 'Quantum Computing',
      abstractText: 'Modeling majority gate logic operations in Quantum-dot Cellular Automata (QCA) to achieve ultra-low power dissipation in next-generation microprocessors.',
      fullPaperBody: 'QCA nano-scale majority logic simulation achieving zero static power dissipation.',
      methodology: 'Quantum-dot Cellular Automata physical interaction simulation.',
      authors: ['Parsa Besharat'],
      date: '2022',
      keywords: ['QCA', 'Quantum Computing', 'Nano-electronics', 'Low Power Logic'],
      journal: 'Microprocessors & Nano-Scale Systems',
    ),
  ];
}
