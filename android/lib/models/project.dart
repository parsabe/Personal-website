class Project {
  final String id;
  final String title;
  final String category;
  final String shortDescription;
  final String fullDescription;
  final List<String> tags;
  final List<String> keyFeatures;
  final String status;
  final String githubUrl;

  const Project({
    required this.id,
    required this.title,
    required this.category,
    required this.shortDescription,
    required this.fullDescription,
    required this.tags,
    required this.keyFeatures,
    required this.status,
    this.githubUrl = 'https://github.com/parsabe',
  });

  static List<Project> get sampleProjects => [
    const Project(
      id: 'vectra',
      title: 'Vectra AI Engine',
      category: 'AI & Vector Processing',
      shortDescription: 'High-performance AI & Vector Search engine for sub-millisecond similarity lookup.',
      fullDescription: 'Vectra is a high-throughput vector database and embedding management framework engineered for real-time similarity search, multi-modal embeddings, and low-latency AI inference integration.',
      tags: ['Vector DB', 'C++', 'Python', 'CUDA', 'AI'],
      keyFeatures: [
        'Sub-millisecond cosine & L2 similarity search',
        'HNSW & IVF index architectures',
        'CUDA GPU acceleration support',
        'REST & gRPC streaming API'
      ],
      status: 'Active / Open Source',
    ),
    const Project(
      id: 'blackwall',
      title: 'Blackwall AI Cyber Defense',
      category: 'Cyber Security & AI',
      shortDescription: 'Autonomous cyber threat intelligence system powered by deep learning and neural monitors.',
      fullDescription: 'Blackwall AI is an autonomous cyber defense platform that continuously monitors network traffic, detects zero-day malware patterns, and executes real-time threat neutralization through adaptive neural networks.',
      tags: ['Cyber Security', 'Deep Learning', 'PyTorch', 'Network Security'],
      keyFeatures: [
        'Real-time anomaly detection in network packets',
        'Zero-day vulnerability prediction model',
        'Autonomous incident response protocol',
        'Interactive AI Analyst chat assistant'
      ],
      status: 'Production Ready',
    ),
    const Project(
      id: 'mlmatrix',
      title: 'MLMatrix Engine',
      category: 'Machine Learning',
      shortDescription: 'High-dimensional tensor calculation engine for scalable linear algebra & neural architectures.',
      fullDescription: 'MLMatrix is a high-performance mathematical computing matrix engine designed for large-scale multi-dimensional tensor transformations, auto-differentiation, and custom neural network layer implementation.',
      tags: ['Tensor Linear Algebra', 'C++', 'Linear Algebra', 'GPU acceleration'],
      keyFeatures: [
        'Auto-differentiation computational graph',
        'Optimized matrix multiplication routines (BLAS / LAPACK)',
        'Distributed tensor sharding across nodes',
        'Custom memory allocator for minimal overhead'
      ],
      status: 'Active Development',
    ),
    const Project(
      id: 'scp',
      title: 'SCP Secure Protocol',
      category: 'Cryptography & Networks',
      shortDescription: 'Next-generation post-quantum secure communication protocol with zero-trust architecture.',
      fullDescription: 'SCP (Secure Communication Protocol) guarantees end-to-end encrypted packet transport with post-quantum key exchange mechanisms (Kyber / Dilithium) and zero-knowledge identity validation.',
      tags: ['Cryptography', 'Rust', 'Networking', 'Post-Quantum'],
      keyFeatures: [
        'Post-quantum lattice-based key exchange',
        'Zero-knowledge proof authentication (ZKP)',
        'Low-overhead packet obfuscation layer',
        'Cross-platform lightweight daemon'
      ],
      status: 'Beta Release',
    ),
    const Project(
      id: 'ceasartoolkit',
      title: 'Ceasar Cryptographic Suite',
      category: 'Security & Forensics',
      shortDescription: 'Comprehensive cryptographic toolkit & binary exploit reverse engineering suite.',
      fullDescription: 'Ceasar Toolkit provides security researchers and penetration testers with advanced cryptanalysis algorithms, automated binary payload generators, and protocol dissectors.',
      tags: ['Reverse Engineering', 'Cryptography', 'Python', 'Exploit Analysis'],
      keyFeatures: [
        'Automated cipher breaking & frequency analysis',
        'Binary disassembly & AST generation',
        'Custom shellcode generator',
        'Interactive terminal GUI'
      ],
      status: 'Active',
    ),
    const Project(
      id: 'parsai',
      title: 'Parsa AI Multi-Agent Environment',
      category: 'Autonomous Agents',
      shortDescription: 'Multi-agent orchestration engine for collaborative problem solving and cognitive workflows.',
      fullDescription: 'Parsa AI is an agentic framework designed to orchestrate autonomous LLM agents working in parallel to solve complex multi-step research, coding, and code-synthesis challenges.',
      tags: ['LLM Orchestration', 'Python', 'Agentic AI', 'LangChain'],
      keyFeatures: [
        'Parallel multi-agent execution loop',
        'Dynamic tool execution & sandboxing',
        'Long-term semantic memory retrieval',
        'Structured output validation'
      ],
      status: 'Active',
    ),
    const Project(
      id: 'netnexus',
      title: 'NetNexus Graph Intelligence',
      category: 'Distributed Systems',
      shortDescription: 'Distributed network topological analyzer & graph neural network engine.',
      fullDescription: 'NetNexus combines graph neural networks (GNNs) with distributed network topology analysis to model complex server networks, map attack paths, and optimize data routing.',
      tags: ['GNN', 'Graph Networks', 'Distributed Systems', 'Python'],
      keyFeatures: [
        'Large-scale graph neural network inference',
        'Attack path discovery & vulnerability mapping',
        'Dynamic topology visualization',
        'High-speed node clustering'
      ],
      status: 'Research Prototype',
    ),
    const Project(
      id: 'hounaartoolkit',
      title: 'Hounaar Creative Algorithmic Framework',
      category: 'Generative Media & Math',
      shortDescription: 'Algorithmic design framework for procedural media synthesis and generative art math.',
      fullDescription: 'Hounaar is a creative programming suite focused on geometric pattern generation, wave function collapse algorithms, and procedural audio-visual synthesis.',
      tags: ['Generative Art', 'Procedural Math', 'WebGL', 'TypeScript'],
      keyFeatures: [
        'Wave function collapse generator',
        'Real-time GLSL shader generator',
        'MIDI & audio-reactive visualizer',
        'Export to SVG / PNG / MP4'
      ],
      status: 'Active',
    ),
    const Project(
      id: 'funroot',
      title: 'FunRoot Cyber Sandbox',
      category: 'Cyber Security',
      shortDescription: 'Penetration testing sandbox & ethical hacking simulation platform.',
      fullDescription: 'FunRoot provides isolated virtual lab environments for testing web application vulnerabilities, privilege escalation vectors, and malware behavior.',
      tags: ['Ethical Hacking', 'Docker', 'Penetration Testing', 'Linux'],
      keyFeatures: [
        'Instant containerized vulnerable target instantiation',
        'Automated scoring & flag submission engine',
        'Network packet capture & analysis',
        'Comprehensive security report generator'
      ],
      status: 'Active',
    ),
    const Project(
      id: 'sandika_proj',
      title: 'Sandika AI Core',
      category: 'AI & Data Intelligence',
      shortDescription: 'Multimodal AI processing portal for voice analytics, code insights & cryptogram solving.',
      fullDescription: 'Sandika Core is an integrated intelligence portal featuring speech-to-text voice analysis, automated Git commit insight evaluation, custom terminology indexing, and puzzle solving.',
      tags: ['Multimodal AI', 'Speech AI', 'Git Analytics', 'Python'],
      keyFeatures: [
        'Acoustic voice log signal analysis',
        'Git repository commit impact scoring',
        'Arkham puzzle solver engine',
        'Custom domain dictionary engine'
      ],
      status: 'Production Live',
    ),
  ];
}
