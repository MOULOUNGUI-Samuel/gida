@extends('layouts.appEmployer')

@section('title', 'Messagerie - GIDA')

@section('content')
    <!-- HEADER UTILISATEUR + CTA -->
    <div class="gida-header" id="gida-header">
      <h1>Messagerie</h1>
      <button class="gida-btn" onclick="window.location.href='{{ route('dashboardEmployer') }}'">← Retour au tableau de bord</button>
    </div>

    <!-- MESSAGERIE INTÉGRÉE AVEC CHATBOT INTELLIGENT -->

    
    
    <style>
      /* Conteneur principal de la messagerie */
      .messagerie-container {
        max-width: 900px;
        margin: 0 auto;
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        overflow: hidden;
        border: 1px solid #e1e5e9;
      }

      /* En-tête du chat avec titre et statut */
      .chat-header {
        background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
        color: white;
        padding: 1.5rem 2rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
      }

      .chat-title {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
      }

      .chat-title h2 {
        margin: 0;
        font-size: 1.3rem;
        font-weight: 600;
      }

      /* Informations du ticket affiché dans l'en-tête */
      .ticket-info {
        font-size: 0.9rem;
        background: rgba(255,255,255,0.2);
        padding: 0.3rem 0.8rem;
        border-radius: 15px;
        opacity: 0.9;
      }

      /* Indicateur de statut en ligne */
      .chat-status {
        display: flex;
        align-items: center;
        gap: 0.5rem;
      }

      .status-indicator {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: #28a745;
        animation: pulse 2s infinite;
      }

      /* Animation de pulsation pour l'indicateur */
      @keyframes pulse {
        0% { box-shadow: 0 0 0 0 rgba(40, 167, 69, 0.7); }
        70% { box-shadow: 0 0 0 10px rgba(40, 167, 69, 0); }
        100% { box-shadow: 0 0 0 0 rgba(40, 167, 69, 0); }
      }

      .status-text {
        font-size: 0.9rem;
        opacity: 0.9;
      }

      /* Zone de contenu du chat */
      .chat-content {
        padding: 2rem;
        background: #f8f9fa;
      }

      /* Zone d'affichage des messages avec scroll */
      .gida-chat-messages {
        max-height: 500px;
        overflow-y: auto;
        margin-bottom: 1rem;
        padding: 1rem;
        background: white;
        border-radius: 8px;
        border: 1px solid #e9ecef;
      }

      /* Style des messages individuels */
      .gida-chat-message {
        margin-bottom: 1.5rem;
        padding: 1rem 1.5rem;
        border-radius: 12px;
        max-width: 80%;
        position: relative;
        animation: messageSlide 0.3s ease-out;
      }

      /* Animation d'apparition des messages */
      @keyframes messageSlide {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
      }

      /* Style des messages du bot */
      .gida-chat-message.bot-message {
        background: linear-gradient(135deg, #e3f2fd 0%, #f3e5f5 100%);
        border: 1px solid #bbdefb;
        margin-right: auto;
      }

      /* Style des messages de l'utilisateur */
      .gida-chat-message.user-message {
        background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
        color: white;
        margin-left: auto;
        text-align: right;
      }

      /* Nom de l'expéditeur du message */
      .gida-chat-user {
        font-weight: 600;
        font-size: 0.9rem;
        display: block;
        margin-bottom: 0.5rem;
        opacity: 0.8;
      }

      /* Contenu du message */
      .gida-chat-text {
        display: block;
        margin-bottom: 0.5rem;
        line-height: 1.5;
        white-space: pre-wrap; /* Préserve les retours à la ligne */
      }

      /* Horodatage du message */
      .gida-chat-time {
        font-size: 0.75rem;
        opacity: 0.7;
        display: block;
      }

      /* Indicateur de frappe du bot */
      .typing-indicator {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 1rem 1.5rem;
        background: #f8f9fa;
        border-radius: 12px;
        margin-bottom: 1rem;
        max-width: 200px;
      }

      /* Points animés de l'indicateur de frappe */
      .typing-dots {
        display: flex;
        gap: 3px;
      }

      .typing-dots span {
        width: 6px;
        height: 6px;
        background: #007bff;
        border-radius: 50%;
        animation: typing 1.4s infinite ease-in-out;
      }

      .typing-dots span:nth-child(1) { animation-delay: -0.32s; }
      .typing-dots span:nth-child(2) { animation-delay: -0.16s; }

      /* Animation des points de frappe */
      @keyframes typing {
        0%, 80%, 100% { transform: scale(0.8); opacity: 0.5; }
        40% { transform: scale(1); opacity: 1; }
      }

      .typing-text {
        font-size: 0.8rem;
        color: #666;
        font-style: italic;
      }

      /* Formulaire de saisie */
      .chat-form {
        background: white;
        border-radius: 8px;
        padding: 1rem;
        border: 1px solid #e9ecef;
      }

      /* Groupe d'éléments de saisie */
      .chat-input-group {
        display: flex;
        gap: 1rem;
        align-items: center;
      }

      /* Champ de saisie de message */
      .chat-input {
        flex: 1;
        padding: 0.875rem 1rem;
        border: 2px solid #e9ecef;
        border-radius: 25px;
        font-size: 1rem;
        transition: all 0.3s ease;
        background: #f8f9fa;
      }

      /* Focus sur le champ de saisie */
      .chat-input:focus {
        outline: none;
        border-color: #007bff;
        background: white;
        box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.1);
      }

      /* Bouton d'envoi principal */
      .chat-send-btn {
        padding: 0.875rem 1.5rem;
        background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
        color: white;
        border: none;
        border-radius: 25px;
        cursor: pointer;
        font-weight: 600;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        min-width: 100px;
        justify-content: center;
      }

      /* Effet hover sur le bouton d'envoi */
      .chat-send-btn:hover:not(:disabled) {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0, 123, 255, 0.3);
      }

      /* État désactivé du bouton */
      .chat-send-btn:disabled {
        opacity: 0.7;
        cursor: not-allowed;
        transform: none;
      }

      /* Icône de chargement */
      .spinner {
        width: 16px;
        height: 16px;
      }

      /* Section d'escalade vers l'administrateur */
      .escalation-section {
        margin-top: 1rem;
        padding: 1rem;
        background: #fff3cd;
        border: 1px solid #ffeaa7;
        border-radius: 8px;
      }

      .escalation-info p {
        margin: 0 0 1rem 0;
        font-weight: 600;
        color: #856404;
      }

      /* Zone de texte pour informations supplémentaires */
      .additional-info-input {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid #ddd;
        border-radius: 4px;
        resize: vertical;
        min-height: 80px;
        margin-bottom: 1rem;
        font-family: inherit;
      }

      /* Boutons d'escalade */
      .escalation-buttons {
        display: flex;
        gap: 1rem;
        align-items: center;
      }

      .escalate-btn {
        background: #28a745;
        color: white;
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 4px;
        cursor: pointer;
        font-weight: 600;
        transition: all 0.3s ease;
      }

      .escalate-btn:hover {
        background: #218838;
        transform: translateY(-1px);
      }

      /* Bouton secondaire */
      .gida-btn-secondary {
        background: #6c757d;
        color: white;
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 4px;
        cursor: pointer;
        transition: all 0.3s ease;
      }

      .gida-btn-secondary:hover {
        background: #5a6268;
      }

      /* Personnalisation de la barre de défilement */
      .gida-chat-messages::-webkit-scrollbar {
        width: 6px;
      }

      .gida-chat-messages::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 3px;
      }

      .gida-chat-messages::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 3px;
      }

      .gida-chat-messages::-webkit-scrollbar-thumb:hover {
        background: #a8a8a8;
      }

      /* Design responsive pour mobile */
      @media (max-width: 768px) {
        .messagerie-container {
          margin: 0 1rem;
        }
        
        .chat-header {
          padding: 1rem;
          flex-direction: column;
          gap: 1rem;
          text-align: center;
        }
        
        .chat-content {
          padding: 1rem;
        }
        
        .gida-chat-message {
          max-width: 90%;
        }
        
        .escalation-buttons {
          flex-direction: column;
        }
      }
    </style>
    <script>
      // Variables globales pour la gestion du chat
      let currentTicketId = null; // ID du ticket en cours
      let isWaitingForResponse = false; // État d'attente de réponse
      
      document.addEventListener('DOMContentLoaded', function() {
        // Récupération des éléments DOM
        const chatForm = document.getElementById('chat-form');
        const messageInput = document.getElementById('message-input');
        const sendBtn = document.getElementById('send-btn');
        const chatMessages = document.getElementById('chat-messages');
        const typingIndicator = document.getElementById('typing-indicator');
        const escalationSection = document.getElementById('escalation-section');
        const escalateBtn = document.getElementById('escalate-btn');
        const cancelEscalationBtn = document.getElementById('cancel-escalation-btn');
        const additionalInfoInput = document.getElementById('additional-info');
        const ticketInfo = document.getElementById('ticket-info');
        const chatTitle = document.getElementById('chat-title');
        
        // Chargement de l'historique des messages au démarrage
        loadChatHistory();
        
        // Message de bienvenue si aucun historique
        setTimeout(() => {
          if (chatMessages.children.length === 0) {
            addBotMessage('Bonjour ! Je suis votre assistant GIDA. Comment puis-je vous aider aujourd\'hui ?');
          }
        }, 1000);

        // Gestionnaire de soumission du formulaire principal
        chatForm.addEventListener('submit', async function(e) {
          e.preventDefault();
          
          const message = messageInput.value.trim();
          if (!message || isWaitingForResponse) return;
          
          // Ajout du message utilisateur à l'interface
          addUserMessage(message);
          
          // Réinitialisation du formulaire et désactivation
          messageInput.value = '';
          setFormState(false);
          
          // Affichage de l'indicateur de frappe
          showTypingIndicator();
          
          try {
            // Envoi du message au serveur
            const response = await fetch('/api/chatbot/message', {
              method: 'POST',
              headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
              },
              body: JSON.stringify({ 
                message: message,
                ticket_id: currentTicketId 
              })
            });
            
            const data = await response.json();
            
            if (data.success) {
              // Simulation d'un délai de réponse réaliste
              setTimeout(() => {
                hideTypingIndicator();
                addBotMessage(data.bot_response, data.timestamp);
                
                // Mise à jour du ticket ID si un nouveau ticket a été créé
                if (data.ticket_id && data.ticket_id !== currentTicketId) {
                  currentTicketId = data.ticket_id;
                  updateTicketDisplay(currentTicketId);
                }
                
                // Affichage du bouton d'escalade si nécessaire
                if (data.show_send_button) {
                  showEscalationSection();
                }
                
                setFormState(true);
              }, 1500 + Math.random() * 1000); // Délai aléatoire entre 1.5-2.5s
            } else {
              throw new Error('Erreur de communication');
            }
          } catch (error) {
            // Gestion des erreurs de communication
            hideTypingIndicator();
            addBotMessage('Désolé, je rencontre un problème technique. Veuillez réessayer dans un moment.');
            setFormState(true);
            console.error('Erreur chat:', error);
          }
        });

        // Gestionnaire du bouton d'escalade
        escalateBtn.addEventListener('click', async function() {
          if (!currentTicketId) {
            alert('Aucun ticket à transmettre');
            return;
          }
          
          const additionalInfo = additionalInfoInput.value.trim();
          
          try {
            // Envoi de la demande d'escalade
            const response = await fetch('/api/chatbot/escalate', {
              method: 'POST',
              headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
              },
              body: JSON.stringify({
                ticket_id: currentTicketId,
                additional_info: additionalInfo
              })
            });
            
            const data = await response.json();
            
            if (data.success) {
              // Confirmation de l'escalade
              addBotMessage(data.message);
              hideEscalationSection();
              
              // Mise à jour du titre pour indiquer la transmission
              chatTitle.textContent = 'Ticket transmis à l\'équipe technique';
            } else {
              alert('Erreur lors de la transmission');
            }
          } catch (error) {
            alert('Erreur de communication');
            console.error('Erreur escalade:', error);
          }
        });

        // Gestionnaire d'annulation de l'escalade
        cancelEscalationBtn.addEventListener('click', function() {
          hideEscalationSection();
          additionalInfoInput.value = '';
        });

        // Fonction pour ajouter un message utilisateur à l'interface
        function addUserMessage(message) {
          const messageDiv = document.createElement('div');
          messageDiv.className = 'gida-chat-message user-message';
          messageDiv.innerHTML = `
            <span class="gida-chat-user">Vous :</span>
            <span class="gida-chat-text">${escapeHtml(message)}</span>
            <span class="gida-chat-time">${getCurrentTime()}</span>
          `;
          chatMessages.appendChild(messageDiv);
          scrollToBottom();
        }

        // Fonction pour ajouter un message du bot à l'interface
        function addBotMessage(message, timestamp = null) {
          const messageDiv = document.createElement('div');
          messageDiv.className = 'gida-chat-message bot-message';
          messageDiv.innerHTML = `
            <span class="gida-chat-user">Assistant GIDA :</span>
            <span class="gida-chat-text">${escapeHtml(message)}</span>
            <span class="gida-chat-time">${timestamp || getCurrentTime()}</span>
          `;
          chatMessages.appendChild(messageDiv);
          scrollToBottom();
        }

        // Affichage de l'indicateur de frappe
        function showTypingIndicator() {
          typingIndicator.style.display = 'flex';
          scrollToBottom();
        }

        // Masquage de l'indicateur de frappe
        function hideTypingIndicator() {
          typingIndicator.style.display = 'none';
        }

        // Affichage de la section d'escalade
        function showEscalationSection() {
          escalationSection.style.display = 'block';
        }

        // Masquage de la section d'escalade
        function hideEscalationSection() {
          escalationSection.style.display = 'none';
        }

        // Gestion de l'état du formulaire (activé/désactivé)
        function setFormState(enabled) {
          messageInput.disabled = !enabled;
          sendBtn.disabled = !enabled;
          isWaitingForResponse = !enabled;
          
          const btnText = sendBtn.querySelector('.btn-text');
          const btnLoading = sendBtn.querySelector('.btn-loading');
          
          if (enabled) {
            btnText.style.display = 'inline';
            btnLoading.style.display = 'none';
            messageInput.focus();
          } else {
            btnText.style.display = 'none';
            btnLoading.style.display = 'inline';
          }
        }

        // Défilement automatique vers le bas
        function scrollToBottom() {
          chatMessages.scrollTop = chatMessages.scrollHeight;
        }

        // Obtention de l'heure actuelle formatée
        function getCurrentTime() {
          return new Date().toLocaleTimeString('fr-FR', { 
            hour: '2-digit', 
            minute: '2-digit' 
          });
        }

        // Échappement HTML pour la sécurité
        function escapeHtml(text) {
          const div = document.createElement('div');
          div.textContent = text;
          return div.innerHTML;
        }

        // Mise à jour de l'affichage du ticket
        function updateTicketDisplay(ticketId) {
          ticketInfo.textContent = `Ticket #${ticketId}`;
          ticketInfo.style.display = 'inline';
          chatTitle.textContent = `Messagerie du ticket #${ticketId}`;
        }

        // Chargement de l'historique des messages
        async function loadChatHistory() {
          try {
            const response = await fetch('/api/chatbot/history');
            const data = await response.json();
            
            if (data.success && data.messages.length > 0) {
              chatMessages.innerHTML = '';
              
              // Reconstruction de l'historique
              data.messages.forEach(msg => {
                if (msg.is_bot) {
                  addBotMessage(msg.message, msg.timestamp);
                } else {
                  addUserMessage(msg.message);
                }
                
                // Récupération du dernier ticket ID
                if (msg.ticket_id && !currentTicketId) {
                  currentTicketId = msg.ticket_id;
                  updateTicketDisplay(currentTicketId);
                }
              });
            }
          } catch (error) {
            console.log('Impossible de charger l\'historique des messages');
          }
        }

        // Focus automatique sur le champ de saisie
        messageInput.focus();
        
        // Envoi avec la touche Entrée
        messageInput.addEventListener('keypress', function(e) {
          if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            if (!isWaitingForResponse) {
              chatForm.dispatchEvent(new Event('submit'));
            }
          }
        });
      });
    </script>
@endsection