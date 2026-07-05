/**
 * Coral Organism Scene - Three.js
 * Fibonacci sphere with displacement-mapped tentacles
 */

class OrganismScene {
  constructor(containerId) {
    this.container = document.getElementById(containerId);
    if (!this.container) return;

    this.scene = new THREE.Scene();
    this.camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 0.1, 1000);
    this.renderer = new THREE.WebGLRenderer({ antialias: true, alpha: true });
    this.renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
    this.renderer.setSize(window.innerWidth, window.innerHeight);
    this.container.appendChild(this.renderer.domElement);

    this.camera.position.z = 10;
    this.organism = new THREE.Group();
    this.scene.add(this.organism);

    this.initLights();
    this.createOrganism();
    this.addEvents();
    this.animate();
  }

  initLights() {
    const ambientLight = new THREE.AmbientLight(0xffffff, 0.5);
    this.scene.add(ambientLight);

    const pointLight = new THREE.PointLight(0xffffff, 1);
    pointLight.position.set(10, 10, 10);
    this.scene.add(pointLight);

    const pointLight2 = new THREE.PointLight(0xd4857a, 1.5);
    pointLight2.position.set(-10, -10, 10);
    this.scene.add(pointLight2);
  }

  createOrganism() {
    // 120 tentacles on fibonacci sphere
    const count = 120;
    const phi = Math.PI * (3 - Math.sqrt(5)); // golden angle in radians

    for (let i = 0; i < count; i++) {
      const y = 1 - (i / (count - 1)) * 2; // y goes from 1 to -1
      const radius = Math.sqrt(1 - y * y); // radius at y

      const theta = phi * i; // golden angle increment

      const x = Math.cos(theta) * radius;
      const z = Math.sin(theta) * radius;

      const tentacleGeo = new THREE.CylinderGeometry(0.05, 0.1, 4, 8);
      tentacleGeo.translate(0, 2, 0); // Origin at base

      const tentacleMat = new THREE.MeshStandardMaterial({
        color: 0xeaaab2,
        roughness: 0.52,
        metalness: 0.1
      });

      const tentacle = new THREE.Mesh(tentacleGeo, tentacleMat);
      
      // Orient tentacle outward
      const pos = new THREE.Vector3(x * 3, y * 3, z * 3);
      tentacle.position.copy(pos);
      tentacle.lookAt(new THREE.Vector3(x * 6, y * 6, z * 6));
      tentacle.rotateX(Math.PI / 2);

      // Randomize initial phase
      tentacle.userData.phase = Math.random() * Math.PI * 2;
      tentacle.userData.originalScaleY = 1;

      this.organism.add(tentacle);
    }
  }

  addEvents() {
    window.addEventListener('resize', () => {
      this.camera.aspect = window.innerWidth / window.innerHeight;
      this.camera.updateProjectionMatrix();
      this.renderer.setSize(window.innerWidth, window.innerHeight);
    });

    // Mouse parallax
    this.mouse = { x: 0, y: 0 };
    window.addEventListener('mousemove', (e) => {
      this.mouse.x = (e.clientX / window.innerWidth) * 2 - 1;
      this.mouse.y = -(e.clientY / window.innerHeight) * 2 + 1;
    });
  }

  animate() {
    requestAnimationFrame(this.animate.bind(this));

    const time = Date.now() * 0.001;

    // Organism rotation
    this.organism.rotation.y += 0.002;
    this.organism.rotation.z += 0.001;

    // Mouse parallax lerp
    this.camera.position.x += (this.mouse.x * 2 - this.camera.position.x) * 0.05;
    this.camera.position.y += (this.mouse.y * 2 - this.camera.position.y) * 0.05;
    this.camera.lookAt(0, 0, 0);

    // Tentacle wave
    this.organism.children.forEach((child, i) => {
      const phase = child.userData.phase;
      const wave = Math.sin(time + phase) * 0.18;
      child.rotation.x += wave * 0.02;
      child.rotation.z += wave * 0.02;
      child.scale.y = 1 + wave * 0.2;
    });

    this.renderer.render(this.scene, this.camera);
  }
}

// Export or initialize if container exists
if (document.getElementById('organism-canvas')) {
  new OrganismScene('organism-canvas');
}
