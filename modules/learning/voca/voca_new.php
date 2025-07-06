<?php
require_once '../../../system/includes/components/Layout.php';

$content = '
<div class="container mx-auto px-4 py-8">
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white/10 backdrop-blur-sm rounded-lg p-6 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-white/60 text-sm">Total Words</p>
                    <p class="text-3xl font-bold text-white" id="totalWords">0</p>
                </div>
                <div class="w-12 h-12 bg-blue-500/20 rounded-lg flex items-center justify-center">
                    <i class="fas fa-book text-blue-300 text-xl"></i>
                </div>
            </div>
        </div>
        <div class="bg-white/10 backdrop-blur-sm rounded-lg p-6 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-white/60 text-sm">This Week</p>
                    <p class="text-3xl font-bold text-white" id="thisWeek">0</p>
                </div>
                <div class="w-12 h-12 bg-green-500/20 rounded-lg flex items-center justify-center">
                    <i class="fas fa-calendar-week text-green-300 text-xl"></i>
                </div>
            </div>
        </div>
        <div class="bg-white/10 backdrop-blur-sm rounded-lg p-6 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-white/60 text-sm">Learning Streak</p>
                    <p class="text-3xl font-bold text-white" id="streak">0</p>
                </div>
                <div class="w-12 h-12 bg-orange-500/20 rounded-lg flex items-center justify-center">
                    <i class="fas fa-fire text-orange-300 text-xl"></i>
                </div>
            </div>
        </div>
        <div class="bg-white/10 backdrop-blur-sm rounded-lg p-6 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-white/60 text-sm">Mastered</p>
                    <p class="text-3xl font-bold text-white" id="mastered">0</p>
                </div>
                <div class="w-12 h-12 bg-purple-500/20 rounded-lg flex items-center justify-center">
                    <i class="fas fa-trophy text-purple-300 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filter -->
    <div class="bg-white/10 backdrop-blur-sm rounded-lg p-6 mb-8">
        <div class="flex flex-col md:flex-row gap-4">
            <div class="flex-1">
                <div class="relative">
                    <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-white/60"></i>
                    <input type="text" id="searchInput" placeholder="Search words..." 
                           class="w-full pl-10 pr-4 py-3 bg-white/20 border border-white/30 rounded-lg text-white placeholder-white/60 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent">
                </div>
            </div>
            <div class="flex gap-2">
                <select id="sortSelect" class="px-4 py-3 bg-white/20 border border-white/30 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-blue-400">
                    <option value="newest">Newest First</option>
                    <option value="oldest">Oldest First</option>
                    <option value="alphabetical">A-Z</option>
                    <option value="reverse">Z-A</option>
                </select>
                <button onclick="showAddWordModal()" class="px-6 py-3 bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition-colors flex items-center">
                    <i class="fas fa-plus mr-2"></i>Add Word
                </button>
            </div>
        </div>
    </div>

    <!-- Words Grid -->
    <div id="wordsContainer" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Words will be loaded here -->
    </div>

    <!-- Loading State -->
    <div id="loadingState" class="text-center py-12">
        <div class="loading-spinner mx-auto mb-4"></div>
        <p class="text-white/60">Loading vocabulary...</p>
    </div>

    <!-- Empty State -->
    <div id="emptyState" class="text-center py-12 hidden">
        <div class="w-24 h-24 bg-white/10 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-book text-white/40 text-3xl"></i>
        </div>
        <h3 class="text-xl font-semibold text-white mb-2">No words yet</h3>
        <p class="text-white/60 mb-6">Start building your vocabulary by adding your first word!</p>
        <button onclick="showAddWordModal()" class="px-6 py-3 bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition-colors">
            <i class="fas fa-plus mr-2"></i>Add Your First Word
        </button>
    </div>
</div>

<!-- Add Word Modal -->
<div id="addWordModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white">Add New Word</h3>
                <button onclick="hideAddWordModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <form id="addWordForm" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Word</label>
                    <input type="text" id="wordInput" required 
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Meaning</label>
                    <input type="text" id="meaningInput" required 
                           class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Example</label>
                    <textarea id="exampleInput" rows="3" 
                              class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"></textarea>
                </div>
                <div class="flex gap-3 pt-4">
                    <button type="button" onclick="hideAddWordModal()" 
                            class="flex-1 px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="flex-1 px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition-colors">
                        Add Word
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Notification -->
<div id="notification" class="fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg transform translate-x-full transition-transform duration-300 z-50">
    <div class="flex items-center">
        <i class="fas fa-check-circle mr-2"></i>
        <span id="notificationText">Word added successfully!</span>
    </div>
</div>
';

$layout = new Layout([
    'pageTitle' => 'Vocabulary Manager',
    'customBackground' => 'gradient-bg',
    'additionalCSS' => '
        <style>
        .card-hover {
            transition: all 0.3s ease;
        }
        .card-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }
        .word-card {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            border: 1px solid #e2e8f0;
            transition: all 0.3s ease;
        }
        .word-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px -5px rgba(0, 0, 0, 0.1);
            border-color: #667eea;
        }
        .floating-action {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            z-index: 1000;
        }
        .search-highlight {
            background: linear-gradient(120deg, #a8edea 0%, #fed6e3 100%);
            padding: 0.1em 0.3em;
            border-radius: 0.3em;
        }
        .loading-spinner {
            border: 3px solid #f3f4f6;
            border-top: 3px solid #667eea;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .fade-in {
            animation: fadeIn 0.5s ease-in;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .dark .word-card {
            background: linear-gradient(135deg, #2d3748 0%, #4a5568 100%);
            border-color: #4a5568;
            color: #e2e8f0;
        }
        .dark .word-card:hover {
            border-color: #667eea;
            background: linear-gradient(135deg, #2d3748 0%, #553c9a 100%);
        }
        .dark .word-card h3 {
            color: #f7fafc;
        }
        .dark .word-card p {
            color: #cbd5e0;
        }
        .dark .word-card .text-gray-500 {
            color: #a0aec0;
        }
        .dark .word-card .text-gray-400 {
            color: #718096;
        }
        .dark .search-highlight {
            background: linear-gradient(120deg, #553c9a 0%, #b794f4 100%);
            color: #1a202c;
        }
        .dark .loading-spinner {
            border-color: #4a5568;
            border-top-color: #667eea;
        }
        </style>
    ',
    'additionalJS' => '
        <script>
        let vocabulary = [];
        let filteredVocabulary = [];

        // Initialize
        document.addEventListener("DOMContentLoaded", () => {
            loadVocabulary();
            setupEventListeners();
        });

        function setupEventListeners() {
            document.getElementById("searchInput").addEventListener("input", filterWords);
            document.getElementById("sortSelect").addEventListener("change", sortWords);
            document.getElementById("addWordForm").addEventListener("submit", handleAddWord);
        }

        async function loadVocabulary() {
            try {
                const response = await fetch("fetch_vocabulary.php");
                const data = await response.json();
                
                if (data.success) {
                    vocabulary = data.vocabulary || [];
                    filteredVocabulary = [...vocabulary];
                    updateStats();
                    renderWords();
                } else {
                    console.error("Failed to load vocabulary:", data.message);
                }
            } catch (error) {
                console.error("Error loading vocabulary:", error);
            }
        }

        function updateStats() {
            document.getElementById("totalWords").textContent = vocabulary.length;
            document.getElementById("thisWeek").textContent = getThisWeekCount();
            document.getElementById("streak").textContent = getStreakCount();
            document.getElementById("mastered").textContent = getMasteredCount();
        }

        function getThisWeekCount() {
            const oneWeekAgo = new Date();
            oneWeekAgo.setDate(oneWeekAgo.getDate() - 7);
            return vocabulary.filter(word => new Date(word.created_at) >= oneWeekAgo).length;
        }

        function getStreakCount() {
            // Simple implementation - can be enhanced
            return Math.min(vocabulary.length, 7);
        }

        function getMasteredCount() {
            // Simple implementation - can be enhanced
            return Math.floor(vocabulary.length * 0.3);
        }

        function renderWords() {
            const container = document.getElementById("wordsContainer");
            const loadingState = document.getElementById("loadingState");
            const emptyState = document.getElementById("emptyState");

            loadingState.classList.add("hidden");

            if (filteredVocabulary.length === 0) {
                emptyState.classList.remove("hidden");
                container.innerHTML = "";
                return;
            }

            emptyState.classList.add("hidden");
            container.innerHTML = filteredVocabulary.map(word => `
                <div class="word-card rounded-lg p-6 fade-in">
                    <div class="flex items-start justify-between mb-4">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white">${highlightSearch(word.word)}</h3>
                        <div class="flex items-center space-x-2">
                            <button onclick="editWord(${word.id})" class="text-blue-500 hover:text-blue-600 transition-colors">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button onclick="deleteWord(${word.id})" class="text-red-500 hover:text-red-600 transition-colors">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                    <p class="text-gray-600 dark:text-gray-300 mb-3">${highlightSearch(word.meaning)}</p>
                    ${word.example ? `<p class="text-sm text-gray-500 dark:text-gray-400 italic">"${word.example}"</p>` : ""}
                    <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-600">
                        <p class="text-xs text-gray-400 dark:text-gray-500">
                            Added: ${new Date(word.created_at).toLocaleDateString()}
                        </p>
                    </div>
                </div>
            `).join("");
        }

        function highlightSearch(text) {
            const searchTerm = document.getElementById("searchInput").value.toLowerCase();
            if (!searchTerm) return text;
            
            const regex = new RegExp(`(${searchTerm})`, "gi");
            return text.replace(regex, "<span class=\"search-highlight\">$1</span>");
        }

        function filterWords() {
            const searchTerm = document.getElementById("searchInput").value.toLowerCase();
            filteredVocabulary = vocabulary.filter(word => 
                word.word.toLowerCase().includes(searchTerm) ||
                word.meaning.toLowerCase().includes(searchTerm) ||
                (word.example && word.example.toLowerCase().includes(searchTerm))
            );
            renderWords();
        }

        function sortWords() {
            const sortBy = document.getElementById("sortSelect").value;
            
            filteredVocabulary.sort((a, b) => {
                switch (sortBy) {
                    case "newest":
                        return new Date(b.created_at) - new Date(a.created_at);
                    case "oldest":
                        return new Date(a.created_at) - new Date(b.created_at);
                    case "alphabetical":
                        return a.word.localeCompare(b.word);
                    case "reverse":
                        return b.word.localeCompare(a.word);
                    default:
                        return 0;
                }
            });
            
            renderWords();
        }

        function showAddWordModal() {
            document.getElementById("addWordModal").classList.remove("hidden");
        }

        function hideAddWordModal() {
            document.getElementById("addWordModal").classList.add("hidden");
            document.getElementById("addWordForm").reset();
        }

        async function handleAddWord(e) {
            e.preventDefault();
            
            const formData = new FormData();
            formData.append("word", document.getElementById("wordInput").value);
            formData.append("meaning", document.getElementById("meaningInput").value);
            formData.append("example", document.getElementById("exampleInput").value);
            
            try {
                const response = await fetch("save_vocabulary.php", {
                    method: "POST",
                    body: formData
                });
                
                const data = await response.json();
                
                if (data.success) {
                    showNotification("Word added successfully!");
                    hideAddWordModal();
                    loadVocabulary();
                } else {
                    showNotification("Failed to add word: " + data.message, "error");
                }
            } catch (error) {
                console.error("Error adding word:", error);
                showNotification("Error adding word", "error");
            }
        }

        async function deleteWord(id) {
            if (!confirm("Are you sure you want to delete this word?")) return;
            
            try {
                const response = await fetch("delete_vocabulary.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({ id: id })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    showNotification("Word deleted successfully!");
                    loadVocabulary();
                } else {
                    showNotification("Failed to delete word: " + data.message, "error");
                }
            } catch (error) {
                console.error("Error deleting word:", error);
                showNotification("Error deleting word", "error");
            }
        }

        function editWord(id) {
            // Implementation for editing word
            showNotification("Edit functionality coming soon!", "info");
        }

        function showNotification(message, type = "success") {
            const notification = document.getElementById("notification");
            const notificationText = document.getElementById("notificationText");
            
            notificationText.textContent = message;
            
            // Update colors based on type
            notification.className = "fixed top-4 right-4 px-6 py-3 rounded-lg shadow-lg transform translate-x-full transition-transform duration-300 z-50";
            
            if (type === "success") {
                notification.classList.add("bg-green-500", "text-white");
            } else if (type === "error") {
                notification.classList.add("bg-red-500", "text-white");
            } else if (type === "info") {
                notification.classList.add("bg-blue-500", "text-white");
            }
            
            notification.classList.remove("translate-x-full");
            
            setTimeout(() => {
                notification.classList.add("translate-x-full");
            }, 3000);
        }
        </script>
    '
]);

$layout->render($content);
?> 